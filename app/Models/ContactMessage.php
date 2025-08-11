<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "email",
        "subject",
        "name",
        "message",
        "reply_message",
        "replyed_at",
        "reply_id",
    ];

    protected function casts(): array
    {
        return [
            'replyed_at' => 'datetime',
        ];
    }

    public function reply()
    {
        return $this->belongsTo(User::class, 'reply_id');
    }
    public function scopeFilter(Builder $builder, ?object $filter = null)
    {
        if (!$filter) {
            return;
        }
        $builder->when($filter->search ?? false, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                $q->whereLike('name', "%{$value}%")
                    ->orWhereLike('email', "%{$value}%");
            });
        });

        $builder->when($filter->status ?? false, function ($query, $value) {
            $value = strtolower($value);
            if ($value == 'not_reply') {
                $query->whereNull('replyed_at');
            }
            if ($value == 'replyed') {
                $query->whereNotNull('replyed_at');
            }
        });
        $builder->when($filter->sort_by ?? false, function ($query, $value) use ($filter) {
            if (in_array($value, ['created_at', 'replyed_at', 'email'])) {
                $orderType = strtolower($filter->sortingOrder) == 'asc' ? 'asc' : 'desc';
                $query->orderBy($value, $orderType);
            }
        });
    }
}
