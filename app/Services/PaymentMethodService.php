<?php

namespace App\Services;

use App\Enums\PaymentGatewayMethodStatus;
use App\Models\PaymentMethod;
use App\PaymentGateways\PaymentGateway;
use App\PaymentGateways\PaymentGatewayFactory;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class PaymentMethodService
{

    /**
     * Get All Payment Methods
     * @return \Illuminate\Support\Collection
     */
    public function getAllPaymentMethods(): Collection
    {
        return PaymentMethod::all();
    }

    /**
     * Update Payment Method
     * @param \App\Models\PaymentMethod $paymentMethod
     * @param array $data
     * @return void
     */
    public function updatePaymentMethod(PaymentMethod $paymentMethod, array $data): void
    {
        $oldIcon = $paymentMethod->icon;

        if (!empty($data['icon'])) {
            $data['icon'] = $paymentMethod->uploadImage($data['icon'], 'PaymentGateways');
        }

        $paymentMethod->update($data);

        if ($oldIcon && !empty($data['icon'])) {
            $paymentMethod->removeImage($oldIcon);
        }
    }

    /**
     * Get Gateway
     * @param string $gateway
     * @return \App\PaymentGateways\PaymentGateway
     */
    public function getGateway(string $gateway): PaymentGateway
    {
        return PaymentGatewayFactory::create($gateway);
    }
}
