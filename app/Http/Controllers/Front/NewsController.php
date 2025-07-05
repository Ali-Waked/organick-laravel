<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NewsController extends Controller
{
    public function __construct(private readonly NewsService $service)
    {
        //
    }
    /**
     * Get All news
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return $this->service->getnews();
    }

    /**
     * Get News Details
     * @param \App\Models\News $news
     * @return \App\Models\News
     */
    public function show(News $news): News
    {
        return $this->service->getNews($news, true);
    }
}
