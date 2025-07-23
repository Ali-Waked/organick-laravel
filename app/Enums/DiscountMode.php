<?php

namespace App\Enums;

enum DiscountMode: string
{
    case FIXED = 'fixed';
    case RANGED = 'ranged';

    public function label(): string
    {
        return match ($this) {
            self::FIXED => 'Fixed Amount',
            self::RANGED => 'Ranged Discount',
        };
    }
}
