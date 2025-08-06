<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\OrderStatus;
use App\Enums\UserTypes;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "driver_price",
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
    protected function numberOfOrders(): Attribute
    {
        return Attribute::make(
            get: fn() => Order::where('status', OrderStatus::Completed)->whereHas('shippingAddress', function ($q) {
                $q->where('city_id', $this->id);
            })->count(),
        );
    }
    protected function numberOfCustomers(): Attribute
    {
        return Attribute::make(
            get: fn() => User::where('type', UserTypes::Customer)->whereHas('billingAddress', function ($q) {
                $q->where('city_id', $this->id);
            })->count(),
        );
    }
    public function scopeFilter(Builder $builder, ?object $filter = null)
    {
        if (!$filter) {
            return;
        }
        $builder->when($filter->name ?? false, function ($query, $value) {
            $query->where('name', 'like', "%{$value}%");
        });

        $builder->when($filter->sort_by ?? false, function ($query, $value) use ($filter) {
            if (in_array($value, ['created_at', 'updated_at', 'name'])) {
                $orderType = strtolower($filter->sortingOrder) == 'asc' ? 'asc' : 'desc';
                $query->orderBy($value, $orderType);
            }
        });
    }
}
