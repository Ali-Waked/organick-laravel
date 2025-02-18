<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "cart_id",
        "product_id",
        "quantity",
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'float',
        ];
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
