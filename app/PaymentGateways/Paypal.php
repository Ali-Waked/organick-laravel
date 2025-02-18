<?php

namespace App\PaymentGateways;

use App\Enums\PaymentMethods;
use App\Models\PaymentMethod;
use Srmklive\PayPal\Facades\PayPal as FacadesPayPal;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Paypal implements PaymentGateway
{
    protected PaymentMethod  $paymentMethod;
    public function __construct()
    {
        $this->paymentMethod = PaymentMethod::where('slug', PaymentMethods::Paypal)->first();
    }
    public function create($order, $user = null): array
    {

        $provider = new PayPalClient;
        $provider->setApiCredentials($this->getOptions());

        // Get an access token
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $payment = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $order->items->sum(function ($item) {
                            return $item->price;
                        }),
                    ]
                ]
            ],
            "application_context" => [
                "cancel_url" => route('payment.cancel', PaymentMethods::Paypal),
                "return_url" => route('payment.success', PaymentMethods::Paypal)
            ]
        ]);

        // dd($payment);
        $approveUrl = '';
        if (isset($payment['id']) && $payment['status'] === 'CREATED') {
            foreach ($payment['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    $approveUrl =  $link['href'];
                }
            }
        }
        return [
            'payment_method_id' => $this->paymentMethod->id,
            'transaction_id' => $payment['id'],
            'payment_method_details' => [
                'name' => PaymentMethods::Paypal->value,
                'transaction_url' => $approveUrl,
                // 'publishable_key' => $this->paymentMethod->options['publishable_key'],
            ],
        ];
    }
    public function captuer($id): void {}
    public function formOptions(): array
    {
        return [
            'client_id' => [
                'label' => 'Paypal Client Id',
                'type' => 'text',
                'placeholder' => 'Paypal Client Id',
                'required' => true,
                'validation' => 'required|string|max:255',
            ],
            'client_secret' => [
                'label' => 'Paypal Client Secret',
                'type' => 'text',
                'placeholder' => 'Paypal Client Secret',
                'required' => true,
                'validation' => 'required|string|max:255',
            ],
        ];
    }
    public function getOptions(): array
    {
        return [
            'mode'    => 'sandbox',
            'sandbox' => [
                'client_id'         => $this->paymentMethod->options['client_id'],
                'client_secret'     => $this->paymentMethod->options['client_secret'],
                // 'app_id'            => 'PAYPAL_LIVE_APP_ID',
            ],

            'payment_action' => 'Sale',
            'currency'       => 'USD',
            'notify_url'     => route('payment.success', PaymentMethods::Paypal),
            'locale'         => 'en_US',
            'validate_ssl'   => false,
        ];
    }
}
