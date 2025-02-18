<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BlogService
{
    public function getBlogs(?string $filter = null): LengthAwarePaginator
    {
        return  Blog::filter(json_decode($filter))->with('category:id,name,slug')->paginate();
    }

    public function store(array $data): Blog
    {
        $data['cover_image'] = Blog::__callStatic('uploadImage', [$data['image'], Blog::FOLDER]);
        return Blog::create($data);
    }

    public function update(Blog $blog, array $data): void
    {
        $cover_image = $blog->cover_image;
        if (isset($data['image'])) {
            $data['cover_image'] = $blog->uploadImage($data['image'], Blog::FOLDER);
        }

        $blog->update($data);

        if ($cover_image && $data['cover_image']) {
            $blog->removeImage($cover_image);
        }
    }

    public function getBlog(Blog $blog): Blog
    {
        $blog->increment('number_of_views');
        return $blog;
    }

    public function showBlog(Blog $blog): Blog
    {
        return $blog;
    }

    public function delete(Blog $blog): void
    {
        $blog->delete();
        $blog->removeImage($blog->cover_image);
    }

    public function getAuthors(): Collection
    {
        return User::has('blogs')->select(['name', 'id'])->get();
    }
}
