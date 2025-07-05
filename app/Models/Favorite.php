<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $fillable = ['product_id'];

    protected static function booted(): void
    {
        static::addGlobalScope('favorite_product', function ($builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUpdatedAtColumn()
    {
        return null;
    }
}
