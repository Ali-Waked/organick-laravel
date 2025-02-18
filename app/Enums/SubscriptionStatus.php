<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Subscribe = 'subscribe';
    case NotSubscribe = 'not_subscribe';
}
