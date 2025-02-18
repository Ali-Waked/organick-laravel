<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Shipped =  'shipped';
    case OutForDelivery = 'out for delivery';
    case Delivered =  'delivered';
    case Completed = 'completed';
    case Canceled = 'canceled';
    case Refunded = 'refunded';
}
