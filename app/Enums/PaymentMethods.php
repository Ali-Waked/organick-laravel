<?php

namespace App\Enums;

enum PaymentMethods: string
{
    case CashOnDelivery = 'cod';
    case Stripe = 'stripe';
    case Paypal = 'paypal';
    case MyFatoorah = 'my_fatoorah';
    case Moyasar = 'moyasar';
}
