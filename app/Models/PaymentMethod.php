<?php

namespace App\Models;

use App\Enums\PaymentGatewayMethodStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService;
use App\Traits\HasImage;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory, HasImage;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
        'options',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'options' => 'array',
            // 'status' => PaymentGatewayMethodStatus::class
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $builder): Builder
    {
        // return $builder->where('status', PaymentGatewayMethodStatus::Active)->select(['id', 'name', 'icon', 'slug']);
        return $builder->where('is_active', true)->select(['id', 'name', 'icon', 'slug']);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function icon(): Attribute
    {
        $imageService = App::make(ImageService::class);
        return Attribute::make(
            get: fn($value) => $imageService->getImageUrl($value),
        );
    }
}
