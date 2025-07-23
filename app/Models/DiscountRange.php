<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRange extends Model
{
    use HasFactory;

    protected $fillable = ['discount_id', 'min_price', 'max_price', 'value', 'type'];


}
