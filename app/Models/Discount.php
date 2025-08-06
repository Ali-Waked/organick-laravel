<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\DiscountFor;
use App\Enums\DiscountMode;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'discount_type', 'discount_value', 'started_at', 'ended_at', 'description', 'is_active', 'discount_for', 'type'];

    public function ranges(): HasMany
    {
        return $this->hasMany(DiscountRange::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'discount_product')->withTimestamps();
    }

    public function scopeFilter(Builder $builder, ?object $data = null)
    {
        if (!isset($data)) {
            return;
        }
        $builder->when($data->search ?? false, function ($builder, $value) {
            return $builder
                ->where(function ($q) use ($value) {
                    $q->whereLike('name', "%{$value}%")
                        ->orWhereLike('description', "%{$value}%");
                });
        });

        $builder->when($data->discount_mode ?? false, function ($builder, $value) use ($data) {
            $value = strtolower($value);
            if (DiscountMode::tryFrom($value)) {
                $builder->where('type', $value);
            }
        });
        $builder->when($data->discount_for ?? false, function ($builder, $value) use ($data) {
            $value = strtolower($value);
            if (DiscountFor::tryFrom($value)) {
                $builder->where('discount_for', $value);
            }
        });
        strtolower($data->sortingOrder ?? '') == 'desc' ? $data->sortingOrder = 'desc' : $data->sortingOrder = 'asc';
        $builder->orderBy('created_at', $data->sortingOrder);
        // info($builder->toSql(), $builder->getBindings());
    }
}