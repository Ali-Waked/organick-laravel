<?php

namespace App\Models;

use App\Models\Scopes\CartScope;
use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    protected static function booted(): void
    {
        // static::observe(CartObserver::class);
        // static::addGlobalScope(CartScope::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_items', 'product_id');
    }
}
