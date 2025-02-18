<?php

namespace App\Enums;

enum PayMethods: string
{
    case Cash = 'cash';
    case PaymentGetway = 'payment_getway';
}
