<?php

namespace App\Services;

use App\Enums\CurrencyCode;
use App\Exceptions\CurrencyApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CurrencyConverterService
{
    protected const BASE_URL = 'https://api.freecurrencyapi.com';

    public function getExchangeRates(): array
    {
        $baseCurrency = Config::get('services.currency_converter.base_currency', 'USD');
        $apiKey = Config::get('services.currency_converter.api_key');
        $cacheKey = 'currency_exchange_rates_' . $baseCurrency;

        return Cache::remember($cacheKey, 20 * 60, function () use ($baseCurrency, $apiKey) {
            $currencyList = implode(',', CurrencyCode::all());
            $response = Http::baseUrl(self::BASE_URL)
                ->withHeaders([
                    'apikey' => $apiKey,
                    'Accept' => 'application/json'
                ])
                ->get("/v1/latest", [
                    'base_currency' => $baseCurrency,
                    'currencies' => $currencyList,
                ]);
            if ($response->successful()) {
                return $response->json()['data'];
            }
            // return $this->handleResponse($response);
            throw new CurrencyApiException('Currency API request failed: ' . $response->body());
        });
    }
}
