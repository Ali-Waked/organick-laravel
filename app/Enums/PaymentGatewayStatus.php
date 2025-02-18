<?php

namespace App\Enums;

enum PaymentGatewayStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
    case Canceled = 'canceled';
}
