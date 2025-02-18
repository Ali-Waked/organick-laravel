<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\CategoryCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\ImageService;
use App\Services\PhotoroomService;
use App\Traits\HasImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $categoryService)
    {
        //
    }

    /**
     * Get And Fitlters Categories
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        return $this->categoryService->getCategoriesWithFilters($request->filter);
    }

    /**
     * Create New Category
     * @param \App\Http\Requests\CategoryRequest $request
     * @param \App\Services\PhotoroomService $photoroomService
     * @return Category
     */
    public function store(CategoryRequest $request): Category
    {
        $category = $this->categoryService->createCategory($request->validated());

        CategoryCreated::dispatch($category);

        return $category;
    }

    /**
     * Get Single Category
     * @param \App\Models\Category $category
     * @return \App\Models\Category
     */
    public function show(Category $category): Category
    {
        return $this->categoryService->getCategory($category);
    }

    /**
     * Update Category
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $this->categoryService->updateCategory($category, $request->validated());
        CategoryCreated::dispatch($category);
        return Response::json([
            'message' => 'Update Category Details Successflly',
        ]);
    }

    /**
     * Remove Category
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->deleteCategory($category);
        return Response::json([
            'message' => 'Delete Category Successflly',
        ]);
    }

    /**
     * Get All Categories Ids, Names and Images
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(Request $request): Collection
    {
        return $this->categoryService->getAllCategories($request->query('except'));
    }
}
