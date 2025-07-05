<?php

namespace App\Models;

use App\Enums\PaymentGatewayStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'transaction_data',
        'status',
        'paymentable_type',
        'paymentable_id',
        'total_price',
        'currency',
        'payment_method_id',
    ];

    protected $append = [
        'paymentMethodName',
    ];

    public function casts(): array
    {
        return [
            'status' => PaymentGatewayStatus::class,
        ];
    }
    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    protected function paymentMethodName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->paymentMethod->name,
        );
    }
}
