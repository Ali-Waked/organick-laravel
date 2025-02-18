<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PaymentMethodController extends Controller
{
    public function __invoke(): Collection
    {
        return PaymentMethod::active()->get();
    }
}
