<?php

namespace App\Services;

use App\Enums\NewsType;
use App\Models\News;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NewsService
{
    public function getNews(?string $filter = null): LengthAwarePaginator
    {
        return News::filter(json_decode($filter))->paginate();
    }

    public function store(array $data): News
    {
        $data['cover_image'] = News::__callStatic('uploadImage', [$data['image'], News::FOLDER]);
        return News::create($data);
    }

    public function update(News $news, array $data): void
    {
        $cover_image = $news->cover_image;
        if (isset($data['image'])) {
            $data['cover_image'] = $news->uploadImage($data['image'], News::FOLDER);
        }
        info(count($data));
        $news->update($data);

        if ($cover_image && isset($data['cover_image'])) {
            $news->removeImage($cover_image);
        }
    }

    public function getSingleNews(News $news, bool $isIncrement = false): News
    {
        if ($isIncrement) {
            $news->increment('number_of_views');
        }
        return $news;
    }

    public function showNews(News $news): News
    {
        return $news;
    }

    public function delete(News $news): void
    {
        $news->delete();
        $news->removeImage($news->cover_image);
    }

    public function getNewsTypes(): array
    {
        return NewsType::cases();
    }

    // public function getAuthors(): Collection
    // {
    //     return User::has('news')->select(['name', 'id'])->get();
    // }
}
