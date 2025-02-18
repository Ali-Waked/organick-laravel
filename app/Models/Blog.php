<?php

namespace App\Models;

use App\Contracts\HasImagable;
use App\Enums\BlogStatus;
use App\Observers\BlogObserver;
use App\Services\ImageService;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory, HasImage;

    const FOLDER = 'blogs';

    protected $appends = [
        'image',
    ];

    protected $hidden = [
        'cover_image',
    ];

    protected $fillable = [
        'number_of_views',
        'cover_image',
        'title',
        'subtitle',
        'text',
        'status',
        'auther', // author
        'user_id',
        'category_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => BlogStatus::class,
            'published_at' => 'datetime',
            'text' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }



    protected static function booted(): void
    {
        static::observe(BlogObserver::class);
    }

    public function scopeFilter(Builder $builder, ?object $data = null)
    {
        if (!isset($data)) {
            return;
        }
        $sortOrder = $data->sortingOrder == 'asc' ? 'asc' : 'desc';
        $builder->when($data->search ?? false, function (Builder $builder, string $value) {
            $builder->whereLike('title', "%$value%");
        });
        $builder->when($data->sort_by ?? false, function (Builder $builder, string $value) use ($sortOrder) {
            $value = Str::snake(strtolower($value));
            if (in_array($value, ['created_at', 'updated_at', 'title', 'published_at', 'number_of_views'])) {
                $builder->orderBy($value, $sortOrder);
            }
            if ($value == 'category_name') {
                return $builder->leftJoin('categories', 'categories.id', '=', 'blogs.id')
                    ->select(['blogs.*'])
                    ->orderBy('categories.name', $sortOrder);
            }
        });
        $builder->when($data->status ?? false, function (Builder $builder, string $value) {
            if (BlogStatus::tryFrom(strtolower($value))) {
                $builder->where('status', $value);
            }
        });
        $builder->when($data->author ?? false, function (Builder $builder, int $value) {
            $builder->where('user_id', $value);
        });
    }
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getImageUrl($this->cover_image),
        );
    }
}
