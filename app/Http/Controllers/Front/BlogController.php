<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $service)
    {
        //
    }
    /**
     * Get All Blogs
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->getBlogs();
    }

    /**
     * Get Blog Details
     * @param \App\Models\Blog $blog
     * @return \App\Models\Blog
     */
    public function show(Blog $blog): Blog
    {
        return $this->service->getBlog($blog);
    }
}
