<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyConverterRequest;
use App\Services\CurrencyConverterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Whoops\Exception\Formatter;

class CurrencyConverterController extends Controller
{

    public function __construct(protected CurrencyConverterService $converter)
    {
        //
    }

    public function getRates(): JsonResponse
    {
        $rates = $this->converter->getExchangeRates();
        $formater = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY_CODE);
        dd($formater->formatCurrency(300, 'ISD'));
        return Response::json($rates);
    }

    public function convert(CurrencyConverterRequest $request): JsonResponse
    {

        return Response::json();
    }
}
