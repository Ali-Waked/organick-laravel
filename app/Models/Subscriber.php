<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'status',
        'email_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
            'email_verified_at' => 'datetime',
        ];
    }

    public function getUpdatedAtColumn(): null
    {
        return null;
    }

    public function scopeAcceptMessage(Builder $builder): void
    {
        $builder->where('status', SubscriptionStatus::Subscribe)->whereNotNull('email_verified_at');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function wasRecentlyCreated(): Attribute
    {
        return Attribute::make(
            get: function () {
                // if($this->email)
            },
        );
    }
}
