<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use App\Services\NewsService;
use App\Services\ImageService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class NewsController extends Controller
{

    public function __construct(protected NewsService $newsService)
    {
        //
    }


    /**
     * Get All Newss
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        return $this->newsService->getNews($request->filter);
    }

    /**
     * Add New News
     * @param \App\Http\Requests\NewsRequest $request
     * @return \App\Models\News
     */
    public function store(NewsRequest $request): News
    {
        return $this->newsService->store($request->validated());
    }

    /**
     * Show News
     * @param \App\Models\News $news
     * @return \App\Models\News
     */
    public function show(News $news): News
    {
        return $this->newsService->getSingleNews($news);
    }

    /**
     * Update News
     * @param \App\Http\Requests\NewsRequest $request
     * @param \App\Models\News $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(NewsRequest $request, News $news): JsonResponse
    {
        $this->newsService->update($news, $request->validated());
        return Response::json([
            'message' => 'updated successfly',
        ]);
    }


    /**
     * Delete News
     * @param \App\Models\News $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(News $news): JsonResponse
    {
        $this->newsService->delete($news);
        return Response::json([
            'message' => "Remove News {$news->title} succssflly",
        ]);
    }

    // /**
    //  * Get All Authors Name
    //  * @return \Illuminate\Support\Collection
    //  */
    // public function getAuthors(): Collection
    // {
    //     return $this->newsService->getAuthors();
    // }

    public function getNewsTypes(): JsonResponse
    {
        return response()->json([
            'types' => $this->newsService->getNewsTypes(),
        ]);
    }
}
