<?php

namespace App\PaymentGateways;

use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentMethods;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Redirect;

class Stripe implements PaymentGateway
{
    protected PaymentMethod  $paymentMethod;
    public function __construct()
    {
        $this->paymentMethod = PaymentMethod::where('slug', PaymentMethods::Stripe)->first();
    }
    public function create($order, $user = null): array
    {
        $stripe = new \Stripe\StripeClient($this->paymentMethod->options['secret_key']);

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $this->getOrderItems($order),
            'mode' => 'payment',
            'success_url' => 'http://localhost:3000/checkout', //route('payment.success', parameters: $this->paymentMethod->slug),
            'cancel_url' => route('payment.cancel', $this->paymentMethod->slug),
        ]);
        return [
            // 'payment_gateway_url' => $checkout_session->url,
            'payment_method_id' => $this->paymentMethod->id,
            'transaction_id' => $checkout_session->id,
            'payment_method_details' => [
                'name' => PaymentMethods::Stripe->value,
                'publishable_key' => $this->paymentMethod->options['publishable_key'],
                'transaction_id' => $checkout_session->id,
            ],
        ];
    }
    public function captuer($id): void
    {
        $stripe = new \Stripe\StripeClient($this->paymentMethod->options['secret_key']);
        $endpoint_secret = 'whsec_68325be8873fbde3d664db09d1b3aeecfcd36c860352199197e62f04b7b21cc0';
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            response(status: 400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            response(status: 400);
            exit();
        }
        switch ($event->type) {
            case 'checkout.session.async_payment_failed':
                $session = $event->data->object;
            case 'checkout.session.async_payment_succeeded':
                $session = $event->data->object;
            case 'checkout.session.completed':
                $session = $event->data->object;
                Payment::where('transaction_id', $session->id)->update(
                    [
                        'transaction_id' => $session->payment_intent
                    ]
                );
            case 'checkout.session.expired':
                $session = $event->data->object;
            case 'payment_intent.amount_capturable_updated':
                $paymentIntent = $event->data->object;
            case 'payment_intent.canceled':
                $paymentIntent = $event->data->object;
            case 'payment_intent.created':
                $paymentIntent = $event->data->object;
            case 'payment_intent.partially_funded':
                $paymentIntent = $event->data->object;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
            case 'payment_intent.processing':
                $paymentIntent = $event->data->object;
            case 'payment_intent.requires_action':
                $paymentIntent = $event->data->object;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $payment = Payment::where('transaction_id', $paymentIntent->id)->first();
                $payment->update([
                    'status' => PaymentGatewayStatus::Completed,
                ]);
                // $payment->order->update([
                //     'status' => OrderSta
                // ]);
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        response(status: 200);
    }
    public function formOptions(): array
    {
        return [
            'publishable_key' => [
                'label' => 'Stripe Publishable Key',
                'type' => 'text',
                'placeholder' => 'Stripe Publishable Key',
                'required' => true,
                'validation' => 'required|string|max:255',
            ],
            'secret_key' => [
                'label' => 'Stripe Secret Key',
                'type' => 'text',
                'placeholder' => 'Stripe Secret Key',
                'required' => true,
                'validation' => 'required|string|max:255',
            ],
        ];
    }
    protected function getOrderItems(Order $order): array
    {
        return  $order->items->map(function ($item) use ($order): array {
            return [
                'price_data' => [
                    'currency' => $order->currency,
                    'product_data' => [
                        'name' => $item->product_name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => $item->quantity * 100,
            ];
        })->toArray();
    }
}
