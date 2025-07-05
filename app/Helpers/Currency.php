<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class Currency
{

    public static function format($amount, $currency = null): string
    {
        $formmater = new \NumberFormatter(Config::get('app.locale'), \NumberFormatter::CURRENCY);
        if ($currency === null) {
            $currency = Config::get('services.currency_converter.base_currency', 'USD');
        }
        return $formmater->formatCurrency($amount, $currency);
    }
}
