<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryService
{
    /**
     * Summary of getCategoriesWithFilters
     * @param string  $filters (json)
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCategoriesWithFilters(?string $filters): LengthAwarePaginator
    {
        return Category::withCount([
            'products as active_products_count' => function ($builder): void {
                $builder->active();
            }
        ])->filter(json_decode($filters))->paginate();
    }

    public function createCategory(array $data): Category
    {
        $cover_image = Category::__callStatic('uploadImage', [$data['image'], Category::FOLDER]);

        $category = Category::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'is_active' => $data['is_active'],
            'parent_id' => $data['parent_id'],
            'is_featured' => $data['is_featured'],
            'cover_image' => $cover_image,
        ]);

        return $category;
    }

    public function getCategory(Category $category): Category
    {
        return $category->append(['image', 'average_rating'])
            ->loadCount('products')
            ->load(['children', 'parent']);
    }

    public function updateCategory(Category $category, array $data): void
    {
        $existingImage = $category->cover_image;
        if (isset($data['image'])) {
            $path = $category->uploadImage($data['image'], Category::FOLDER);
        }
        $category->update($data);

        if (isset($path) && $existingImage) {
            $category->removeImage($existingImage);
        }
    }

    public function deleteCategory(Category $category): void
    {
        $category->delete();
        $category->removeImage($category->cover_image);
    }

    public function getAllCategories(?int $id = 0): Collection
    {
        $category = Category::where('id', $id)->first();
        $ids = [];
        if ($category) {
            $ids = $category->descendants()->pluck('id')->toArray();
            array_push($ids, $category->id);
        }
        return Category::select(['id', 'name'])->active()->whereNotIn('id', $ids)->get();
    }
}
