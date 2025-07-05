<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
