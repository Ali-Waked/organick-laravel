<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Observers\ProductObserver;
use App\Traits\HasImage;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasImage;
    const FOLDER = 'product';
    const ACTIVE = 'active';

    protected $fillable = [
        'name',
        'slug',
        'cover_image',
        'description',
        'price',
        'quantity',
        'is_active',
        'is_featured',
        'low_stock_threshold',
        'category_id',
        'discount_id',
    ];
    protected $hidden = [
        'cover_image',
    ];

    protected $appends = [
        'image',
    ];

    protected static function booted(): void
    {
        static::observe(ProductObserver::class);
    }


    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // public function discount(): BelongsTo
    // {
    //     return $this->belongsTo(Discount::class);
    // }
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discount_product')->latest('discount_product.created_at')
            ->limit(1);
        ;
    }


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function feedbacks(): MorphMany
    {
        return $this->morphMany(Feedback::class, 'assessable');
    }

    public function getAverageRatingAttribute()
    {
        return $this->feedbacks()->avg('rating');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class)
            ->whereRelation('order', 'status', '=', OrderStatus::Completed);
    }
    protected function totalRequests(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->orderItems()->sum('quantity'),
        );
    }
    protected function currentDiscount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discounts()
                ->where('is_active', true)
                ->whereDate('ended_at', '>', now())
                ->latest('discount_product.created_at')
                ->first(),
        );
    }
    public function getFinalPriceAttribute(): float
    {
        $price = $this->price;
        $discount = $this->current_discount;

        if ($discount) {
            if ($discount->type === 'fixed') {
                $price -= $discount->value;
            } elseif ($discount->type === 'percentage') {
                $price -= ($this->price * ($discount->value / 100));
            }
        }

        return max($price, 0);
    }

    public function userReviews(int $userId = null, ?DateTime $dateTime = null): MorphMany
    {
        return $this->evaluations()->where('user_id', $userId ?: Auth::id())
            ->when($dateTime, function (Builder $builder, $value) {
                $builder->orWhereDate('updated_at', '<', $value);
            });
    }
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getImageUrl($this->cover_image),
        );
    }

    public function getIsFavoriteAttribute(): bool
    {
        return
            Auth::check() && Auth::user()->favorites()->where('product_id', $this->id)->exists();
    }
    protected function getCustomerPurchasedAttribute(): bool
    {
        return Auth::check() && Auth::user()->orders()->where('status', OrderStatus::Completed)->whereHas('items', function ($query) {
            $query->where('product_id', $this->id);
        })->exists();
    }
    public function getCanRateAttribute(): bool
    {
        if (!Auth::check())
            return false;
        $existing = Auth::user()->feedbacks()->where('assessable_type', 'product')
            ->where('assessable_id', $this->id)
            ->first();
        return now()->lessThanOrEqualTo($existing->editable_until ?? now()->subDay()) || $this->getCustomerPurchasedAttribute();
    }
    public function scopeFilter(Builder $builder, ?object $data = null)
    {
        if (!isset($data)) {
            return;
        }
        $builder->when($data->name, function ($builder, $value) {
            return $builder->where(function ($builder) use ($value) {
                $builder->whereLike('products.name', "%$value%")
                    ->orWhereRelation('category', 'name', 'like', "%$value%");
            });
        });

        $builder->when($data->sort_by, function ($builder, $value) use ($data) {
            $sortingOrder = 'asc';
            strtolower($data->sortingOrder) == $sortingOrder ?: $sortingOrder = 'desc';
            $value = Str::snake(strtolower($value));
            if (in_array($value, ['name', 'price', 'created_at'])) {
                return $builder->orderBy("products.{$value}", $sortingOrder);
            }
            if ($value == 'category_name') {
                return $builder->leftJoin('categories', 'categories.id', '=', 'products.id')
                    ->select(['products.*'])
                    ->orderBy('categories.name', $sortingOrder);
            }
            if ($value == 'best_seller') {
                $this
                    ->withSum('orderItems as total_sold', 'quantity')
                    ->orderBy('name', $sortingOrder);
            }
        });
        $builder->when($data->status ?? false, function ($builder, $value) {
            // if (strtolower($value) == 'active') {
            //     return $builder->where('is_active', true);
            // }
            // return $builder->where('is_active', false);
            $value = strtolower($value);
            if (in_array($value, ['active', 'archived'])) {
                return $builder->where('is_active', $value == self::Active);
            }
        });
    }

    public function scopeSearch(Builder $builder, ?object $data = null)
    {
        if (!isset($data)) {
            return;
        }
        $builder->when($data->name ?? false, function ($builder, $value) {
            return $builder->where(function ($builder) use ($value) {
                $builder->whereLike('products.name', "%$value%")
                    ->orWhereRelation('category', 'name', 'like', "%$value%");
            });
        });

        $builder->when($data->category_id ?? false, function ($builder, $value) {
            return $builder->where('category_id', $value);
        });

        $builder->when($data->sort_by ?? false, function ($builder, $value) use ($data) {
            $sortingOrder = 'asc';
            strtolower($data->sortingOrder ?? false) == $sortingOrder ?: $sortingOrder = 'desc';
            $value = Str::snake(strtolower($value));
            if (in_array($value, ['name', 'price', 'created_at'])) {
                return $builder->orderBy("products.{$value}", $sortingOrder);
            }
            if ($value == 'category_name') {
                return $builder->leftJoin('categories', 'categories.id', '=', 'products.id')
                    ->select(['products.*'])
                    ->orderBy('categories.name', $sortingOrder);
            }
            if ($value == 'best_seller') {
                $this
                    ->withSum('orderItems as total_sold', 'quantity')
                    ->orderBy('name', $sortingOrder);
            }
        });
    }
    public function scopeActive(Builder $builder): void
    {
        $builder->where('is_active', true);
    }
}
