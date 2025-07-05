<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __invoke()
    {
        return City::select('id', 'name', 'driver_price')->get();
    }
}
