<?php

namespace App\Models;

use App\Observers\CategoryObserver;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory, HasImage, NodeTrait;

    const FOLDER = 'categories';
    const Active = 'active';

    protected $fillable = [
        'name',
        'description',
        'slug',
        'cover_image',
        'is_active',
        'is_featured',
        'parent_id',
        '_left',
        '_right',
    ];

    protected $hidden = [
        'cover_image',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    protected function casts(): array
    {
        return [
            // 'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::observe(CategoryObserver::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getImageUrl($this->cover_image),
        );
    }

    protected function averageRating(): Attribute
    {
        return Attribute::get(function () {
            return $this->products()
                ->where('feedbacks.assessable_type', 'product')
                ->join('feedbacks', 'feedbacks.assessable_id', '=', 'products.id')
                ->avg('feedbacks.rating') ?? 0;
        });
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('is_active', true);
    }

    public function scopeFilter(Builder $builder, ?object $data = null)
    {
        $builder->when($data?->name ?? false, function ($builder, $value) {
            return $builder->whereLike('name', "%$value%");
        });

        $builder->when($data?->sort_by ?? false, function ($builder, $value) use ($data) {
            strtolower($data->sortingOrder) == 'desc' ? $data->sortingOrder = 'desc' : $data->sortingOrder = 'asc';
            $value = Str::snake(strtolower($value));
            if (in_array($value, ['name', 'created_at'])) {
                return $builder->orderBy($value, $data->sortingOrder);
            }
            if ($value == 'number_of_active_product') {
                return $builder->withCount([
                    'products as product_active_count' => function ($builder) {
                        $builder->where('is_active', true);
                    }
                ])->orderBy('product_active_count', $data->sortingOrder);
            }
        });
        $builder->when($data?->status ?? false, function ($builder, $value) {
            $value = strtolower($value);
            if (in_array($value, ['active', 'archived'])) {
                // $is_active = $value == 'active';
                // Log::info("message");
                // info('is active', [$is_active, 'status', $value]);
                return $builder->where('is_active', $value == self::Active);
            }
        });
    }
}
