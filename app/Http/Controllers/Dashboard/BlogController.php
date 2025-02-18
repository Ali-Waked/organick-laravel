<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Services\BlogService;
use App\Services\ImageService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class BlogController extends Controller
{

    public function __construct(protected BlogService $blogService)
    {
        //
    }


    /**
     * Get All Blogs
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        return $this->blogService->getBlogs($request->filter);
    }

    /**
     * Add New Blog
     * @param \App\Http\Requests\BlogRequest $request
     * @return \App\Models\Blog
     */
    public function store(BlogRequest $request): Blog
    {
        return $this->blogService->store($request->validated());
    }

    /**
     * Show Blog
     * @param \App\Models\Blog $blog
     * @return \App\Models\Blog
     */
    public function show(Blog $blog): Blog
    {
        return $this->blogService->showBlog($blog);
    }

    /**
     * Update Blog
     * @param \App\Http\Requests\BlogRequest $request
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BlogRequest $request, Blog $blog): JsonResponse
    {
        $this->blogService->update($blog, $request->validated());
        return Response::json([
            'message' => 'updated successfly',
        ]);
    }


    /**
     * Delete Blog
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Blog $blog): JsonResponse
    {
        $this->blogService->delete($blog);
        return Response::json([
            'message' => "Remove Blog {$blog->title} succssflly",
        ]);
    }

    /**
     * Get All Authors Name
     * @return \Illuminate\Support\Collection
     */
    public function getAuthors(): Collection
    {
        return $this->blogService->getAuthors();
    }
}
