<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Symfony\Component\Intl\Countries;

class OrderAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'type',
        'city',
        'country',
        'postal_code',
        'state',
        'street',
        'phone_number',
    ];

    protected function country(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Countries::getName($value)
        );
    }
}
