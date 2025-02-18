<?php

namespace App\Http\Controllers;

use App\Actions\GetLocationByIp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Intl\Countries;

class CountryController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {

        $countries = Countries::getNames();
        return Response::json([
            'countries' => $countries,
            'userInfrmation' => GetLocationByIp::execute($request->ip()),
        ]);
    }
}
