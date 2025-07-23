<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteFeedback extends Model
{
    use HasFactory;
    protected $table = 'site_feedback';
    protected $fillable = [
        'customer_id',
        'rating',
        'comment',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('sortFeedback', function ($query) {
            $query->orderByDesc('created_at');
        });
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function scopeFilter($query, ?object $data = null)
    {
        if (!isset($data)) {
            return;
        }
        $query->when($data->search ?? false, function ($query, $value) {
            return $query->whereHas('customer', function ($query) use ($value) {
                $query->whereLike('first_name', "%$value%")
                    ->orWhereLike('last_name', "%$value%")
                    ->orWhereLike('email', "%$value%");
            });
        });

        $query->when(isset($data->is_featured), function ($query) use ($data) {
            return $query->where('is_featured', (bool) $data->is_featured);
        });

        strtolower($data->sortingOrder ?? '') == 'desc' ? $data->sortingOrder = 'desc' : $data->sortingOrder = 'asc';
        $query->orderBy('created_at', $data->sortingOrder);
    }
}
