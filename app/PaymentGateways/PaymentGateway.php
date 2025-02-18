<?php

namespace App\PaymentGateways;

use App\Models\Payment;

interface PaymentGateway
{
    /**
     * Create
     * @return string
     */
    public function create($order, $user = null): array;

    /**
     * Summary of cluster
     * @return \App\Models\Payment
     */
    public function captuer($id): void;

    /**
     * Summary of options
     * @return array
     */
    public function formOptions(): array;
}
