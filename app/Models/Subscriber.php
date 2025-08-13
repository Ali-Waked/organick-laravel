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
    'is_subscribed',
    'email_verified_at',
    'user_id',
    'verification_token',
  ];

  protected function casts(): array
  {
    return [
      'is_subscribed' => 'boolean',
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

  public function scopeFilter(Builder $builder, object $filter)
  {
    if (isset($filter->search)) {
      $builder->whereLike('email', '%' . $filter->search . '%');
    }

    if (isset($filter->status)) {
      $value = strtolower($filter->status);
      if ($value == 'subscribed') {
        $builder->where('is_subscribed', true);
      } elseif ($value == 'unsubscribed') {
        $builder->where('is_subscribed', false);
      }
    }

    if (isset($filter->sort_by)) {
      $value = strtolower($filter->sort_by);
      $sorting_order = $filter->sortingOrder == 'asc' ? 'asc' : 'desc';
      if (in_array($value, ['email', 'created_at'])) {
        $builder->orderBy($value, $sorting_order);
      } else {
        $builder->orderBy('created_at', $sorting_order);
      }
    }

    return $builder;
  }
}
