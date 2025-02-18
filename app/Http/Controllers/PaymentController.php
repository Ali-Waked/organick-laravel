<?php

namespace App\Http\Controllers;

use App\Enums\PaymentGatewayStatus;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        //
    }
    public function store()
    {
        // Payment::forceCreate([
        //     'paymentable_type' =>  'order',
        //     'pymentable_id' => $order->id,
        //     'payment_method_id' => $this->paymentMethod->id,
        //     'currency' => $order->currency,
        //     'total_price' => 40,
        //     'transaction_id' => $response->id,
        // ]);
    }
    public function success(Request $request, PaymentMethod $paymentMethod)
    {
        $stripe = new \Stripe\StripeClient(PaymentMethod::where('slug', 'stripe')->first()->options['secret_key']);
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
            Log::error('Invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
                // case 'checkout.session.async_payment_failed':
                //     $session = $event->data->object;
                // case 'checkout.session.async_payment_succeeded':
                //     $session = $event->data->object;
            case 'checkout.session.completed':
                $session = $event->data->object;
                Payment::where('transaction_id', $session->id)->update(
                    [
                        'transaction_id' => $session->payment_intent
                    ]
                );
                Log::info('Payment completed for session: ' . $session->id);
                break;
                // case 'checkout.session.expired':
                //     $session = $event->data->object;
                // case 'payment_intent.amount_capturable_updated':
                //     $paymentIntent = $event->data->object;
                // case 'payment_intent.canceled':
                //     $paymentIntent = $event->data->object;
                // case 'payment_intent.created':
                //     $paymentIntent = $event->data->object;
                // case 'payment_intent.partially_funded':
                //     $paymentIntent = $event->data->object;
                // case 'payment_intent.payment_failed':
                //     $paymentIntent = $event->data->object;
                // case 'payment_intent.processing':
                //     $paymentIntent = $event->data->object;
                // case 'payment_intent.requires_action':
                //     $paymentIntent = $event->data->object;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

                if ($payment) {
                    Log::info('Payment succeeded for payment ID: ' . $payment->id);
                    $payment->update([
                        'status' => PaymentGatewayStatus::Completed->value,
                    ]);
                } else {
                    Log::warning('No payment found for transaction ID: ' . $paymentIntent->id);
                }
                break;
            default:
                Log::info('Received unknown event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }
    public function cancle(Request $request, PaymentMethod $paymentMethod) {}
}
