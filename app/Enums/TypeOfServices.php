<?php

namespace App\Enums;

enum TypeOfServices: int
{
    case DAIRY_PRODUCTS = 1;

    case STORE_SERVICES = 2;

    case DELIVERY_SERVICES = 3;

    case AGRICULTURAL_SERVICES = 4;

    case ORGANIC_PRODUCTS = 5;

    case FRESH_VEGETABLES = 6;
}
