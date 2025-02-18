<?php

namespace App\PaymentGateways;

use App\Enums\PaymentMethods;
use Illuminate\Support\Str;

class PaymentGatewayFactory
{
    public static function create(string $gateway)
    {
        $class =  'App\PaymentGateways\\' . Str::studly($gateway);
        try {
            return new $class();
        } catch (\Exception $e) {
            // throw  new \Exception("Invalid payment gateway: $gateway");
        }
    }
}
