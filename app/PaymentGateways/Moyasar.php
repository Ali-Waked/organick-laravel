<?php

namespace App\PaymentGateways;

use App\Enums\PaymentMethods;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;

class Moyasar implements PaymentGateway
{
    protected PaymentMethod  $paymentMethod;
    public function __construct()
    {
        $this->paymentMethod = PaymentMethod::where('slug', PaymentMethods::Moyasar)->first();
        // \Stripe\Stripe::setApiKey($this->paymentMethod->options['secret_key']);
        // dd($this->paymentMethod->options);
    }
    public function create($order, $user = null): array
    {
        // $stripe = new \Stripe\StripeClient($this->paymentMethod->options['secret_key']);
        // $orderItems = $order->items->map(function ($item) use ($order): array {
        //     return [
        //         'price_data' => [
        //             'currency' => $order->currency,
        //             'product_data' => [
        //                 'name' => $item->product_name,
        //             ],
        //             'unit_amount' => $item->price,
        //         ],
        //         'quantity' => $item->quantity * 100,
        //     ];
        // })->toArray();
        // $paymentData = [
        //     'amount' => 20,
        //     'currency' => 'usd',
        //     'description' => '',
        //     // 'source' => 'credit_card', // Set your payment source here
        //     'source' => [
        //         'type' => 'token',
        //         'token' => $request->input('token'), // The token from the frontend
        //         '3ds' => true,
        //         'manual' => false,
        //     ],
        //     'redirect' => route('payment.success', 'moyasar'), // Your callback URL
        // ];
        // $response  = Http::post('https://api.moyasar.com/v1/payments', [
        //     'auth' => ['sk_test_wEJ8mHtmJ6wbMTci4VdHr6DotLH9xz1sRa7fGmhg', ''],
        //     'json' => $paymentData,
        // ]);
        $tokenData = [
            'name' => 'ali waked',
            'number' => '4111111111111111',
            'month' => '09',
            'year' => '27',
            'cvc' => '911',
            'callback_url' => route('payment.success', 'moyasar'),
        ];
        // $response = Http::post('https://api.moyasar.com/v1/tokens', [
        //     'auth' => ['sk_test_wEJ8mHtmJ6wbMTci4VdHr6DotLH9xz1sRa7fGmhg', ''],
        //     'form_params' => $tokenData,
        // ]);
        $response = Http::post('https://api.moyasar.com/v1/payments', [
            'amount' => 10000, // Amount in halalas (100.00 SAR)
            'currency' => 'SAR',
            'source' => 'creditcard',
            'callback_url' => route('payment.success', 'moyasar'),
            'description' => 'Order #1234',
        ]);
        dd($response);
        $html = <<<HTML
                <p>hadi</p>
                HTML;
        $responseBody = json_decode($response->getBody()->getContents(), true);
        return [$responseBody['payment_url']];
    }
    public function captuer($id): void {}
    public function formOptions(): array
    {
        return [
            'publishable_key' => [
                'label' => 'Moyasar Publishable Key',
                'type' => 'text',
                'placeholder' => 'Moyasar Publishable Key',
                'required' => true,
                'validation' => 'required|string|max:255',
            ],
            'secret_key' => [
                'label' => 'Moyasar Secret Key',
                'type' => 'text',
                'placeholder' => 'Moyasar Secret Key',
                'required' => true,
                'validation' => 'required|string|max:255',
            ],
        ];
    }
}
// pk_test_Nwn6XyfzMRQhn8CLzNA8YDo6MPCV9mSWJifcucZ9
// sk_test_wEJ8mHtmJ6wbMTci4VdHr6DotLH9xz1sRa7fGmhg
