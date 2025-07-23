<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    public $table = 'feedbacks';
    protected $fillable = [
        'rating',
        'comment',
        'editable_until',
        'user_id',
        'assessable_id',
        'assessable_type',
    ];

    protected function casts(): array
    {
        return [
            'editable_until' => 'datetime',
        ];
    }
    protected static function booted(): void
    {
        static::addGlobalScope('sortFeedback', function ($query) {
            $query->orderByRaw("CASE WHEN user_id = ? THEN 0 ELSE 1 END", [auth()->id()])
                ->orderByDesc('created_at');
        });
    }
    public function assessable()
    {
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
