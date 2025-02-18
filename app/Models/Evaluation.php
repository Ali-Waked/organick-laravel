<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment',
        'rating',
        'assessable_type',
        'assessable_id'
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
        ];
    }
    public function assessable(): MorphTo
    {
        return $this->morphTo();
    }
}
