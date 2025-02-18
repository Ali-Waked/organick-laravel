<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
// use Symfony\Component\Intl\Countries;

class GetLocationByIp
{

    public static function execute(string $ip): ?array
    {
        $apiKey = config('services.ipinfo.key');
        $response = Http::withHeader('Accept', 'application/json')->get("https://ipinfo.io/193.35.22.139/json?token={$apiKey}");

        if ($response->successful()) {
            $data = $response->json();
            return [
                'country' =>  $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'timezone' => $data['timezone'] ?? null,
                'region' => $data['region'] ?? null,
            ];
        }

        return null;
    }
}
