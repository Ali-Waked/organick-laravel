<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;

class PaymentService
{
    public function create(Order $order, array $data): Payment
    {
        return Payment::create([
            'paymentable_type' =>  'order',
            'paymentable_id' => $order->id,
            'payment_method_id' => $data['payment_method_id'],
            'currency' => $order->currency,
            'total_price' => $order->amount,
            'transaction_id' => $data['transaction_id'],
        ]);
    }
}
