<?php

namespace App\Models;

use App\Contracts\HasImagable;
use App\Enums\NewsStatus;
use App\Observers\NewsObserver;
use App\Services\ImageService;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory, HasImage;

    const FOLDER = 'news';

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
        'content',
        // 'status',
        'type',
        'slug',
        // 'auther', // author
        'created_by',
        // 'category_id',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            // 'status' => NewsStatus::class,
            'published_at' => 'datetime',
            'content' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // public function category(): BelongsTo
    // {
    //     return $this->belongsTo(Category::class);
    // }



    protected static function booted(): void
    {
        static::observe(NewsObserver::class);
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
            // if ($value == 'category_name') {
            //     return $builder->leftJoin('categories', 'categories.id', '=', 'news.id')
            //         ->select(['news.*'])
            //         ->orderBy('categories.name', $sortOrder);
            // }
        });
        // $builder->when($data->is_published ?? false, function (Builder $builder, string $value) {
        //     // if (NewsStatus::tryFrom(strtolower($value))) {
        //     $builder->where('is_published', $value);
        //     // }
        // });
        if (isset($data->is_published) && $data->is_published !== null) {
            $builder->where('is_published', $data->is_published);
        }
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
