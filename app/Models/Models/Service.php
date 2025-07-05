<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use App\Enums\TypeOfServices;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends Model
{
    use HasFactory, HasImage;

    const FOLDER = 'services';

    public $timestamps = false;

    protected $appends = ['image'];

    protected $fillable = [
        'name',
        'description',
        'slug',
        'status',
        'icon',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function casts(): array
    {
        return [
            'status' => ServiceStatus::class,
            'code' => TypeOfServices::class,
        ];
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getImageUrl($this->icon),
        );
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'service_id');
    }

    public function scopeFilter(Builder $builder, ?object $filter = null)
    {
        if (!$filter) {
            return;
        }
        $builder->when($filter->search ?? false, function (Builder $builder, string $value) {
            $builder->whereLike('name', "%$value%");
        });
    }
}
