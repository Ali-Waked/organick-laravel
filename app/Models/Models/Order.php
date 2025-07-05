<?php

namespace App\Models;

use App\Enums\CurrencyCode;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\PayMethods;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Address;
use App\Models\Payment;
use Attribute as GlobalAttribute;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'number',
        'status',
        'method',
        'payment_status',
        'currency',
        'is_paid',
        'user_id',
        'driver_id',
        'assigned_by_id',
    ];

    protected $appends = [
        'amount',
    ];

    protected static function booted(): void
    {
        static::observe(OrderObserver::class);
    }
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_status' => OrderPaymentStatus::class,
            'currency' => CurrencyCode::class,
            'number' => 'integer',
            'is_paid' => 'boolean',
        ];
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function shippingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }
    protected function amount(): Attribute
    {
        return Attribute::make(get: fn() => $this->items()->sum(DB::raw('quantity * price')));
    }
    // public function scopeTotal(Builder $builder): Builder
    // {
    //     return $builder->selectSub(function ($query) {
    //         // $->sum(function (OrderItem $item): float {
    //         //     return $item->quantity * $item->price;
    //         // });
    //         $query->select(DB::raw('SUM(quantity * price)'))->from('order_items')->whereColumn('order_items.order_id', 'orders.id');
    //         // ->selectSub(function ($query) {
    //         //     $query->select(DB::raw('SUM(quantity * price)'))
    //         //         ->from('order_items')
    //         //         ->whereColumn('order_items.order_id', 'orders.id');
    //         // }, 'total_price')
    //     }, 'total_price');
    // }

    // public function totalPrice():Attribute {
    //     return Attribute::make(
    //         get: fn() => $this->items
    //     )
    // }
    public function scopeFilter(Builder $builder, ?object $filter)
    {
        if (!isset($filter)) {
            return;
        }
        $builder->when($filter->search ?? false, function ($builder, $value) {
            return $builder->where(function ($builder) use ($value) {
                $builder->where('number', 'like', "$value%")
                    ->orWhereRelation('customer', 'email', 'like', "%$value%");
            });
        });
        $builder->when($filter->status ?? false, function ($builder, $value) {
            if (OrderStatus::tryFrom(strtolower($value))) {
                return $builder->where('status', $value);
            }
        });
        $builder->when($filter->sorting_by ?? false, function ($builder, $value) use ($filter) {
            $sortingOrder = ($filter->sorting_order ?? '') == 'asc' ? 'asc' : 'desc';
            // return $builder->orderBy('desc');
            return $builder->orderBy($value, $sortingOrder);
            $value = Str::snake(strtolower($value));
            if (in_array($value, ['created_at', 'number'])) {
            }
            if ($value == 'total_price') {
                // return $builder->orderBy('total', $sortingOrder);
            }
        });
    }
}
