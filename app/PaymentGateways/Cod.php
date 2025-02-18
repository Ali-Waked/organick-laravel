<?php

namespace App\PaymentGateways;

use App\Enums\PaymentMethods;
use App\Models\PaymentMethod;

class Cod implements PaymentGateway
{
    protected PaymentMethod  $paymentMethod;
    public function __construct()
    {
        $this->paymentMethod = PaymentMethod::where('slug', PaymentMethods::CashOnDelivery)->first();
    }
    public function create($order, $user = null): array
    {
        return [
            'payment_method_id' => $this->paymentMethod->id,
            'payment_method_details' => ['name' => PaymentMethods::CashOnDelivery->value],
            'transaction_id' => null,
        ];
    }
    public function captuer($id): void {}
    public function formOptions(): array
    {
        return [];
    }
}
