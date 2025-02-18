<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Subscription extends Model
{
    use HasFactory, Notifiable;

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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
