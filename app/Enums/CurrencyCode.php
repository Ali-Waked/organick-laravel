<?php

namespace App\Enums;

enum CurrencyCode: string
{

    case ILS = 'ILS'; // Israeli New Sheqel
    case EUR = 'EUR'; // Euro
    case GBP = 'GBP'; // British Pound Sterling
    case USD = 'USD'; //US Dollar

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
