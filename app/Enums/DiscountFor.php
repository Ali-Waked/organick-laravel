<?php

namespace App\Enums;

enum DiscountFor: string
{
    case ORDER = 'order';
    case PRODUCT = 'product';

    public function label(): string
    {
        return match ($this) {
            self::ORDER => 'Order Discount',
            self::PRODUCT => 'Product Discount',
        };
    }
}
