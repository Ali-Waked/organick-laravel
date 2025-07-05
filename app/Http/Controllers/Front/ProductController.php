<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    public function index(Request $request): LengthAwarePaginator
    {
        $products = Product::select('id', 'category_id', 'slug', 'name', 'cover_image', 'price')
            ->search(json_decode($request->filter))
            ->with(['category:id,name'])
            ->paginate(12);
        $products->getCollection()->each->append('isFavorite');
        return $products;
    }
    public function show(Product $product): Product
    {
        return $product->load(['category:id,name'])->append('isFavorite');
    }
    public function getRelatedProducts(Category $category): LengthAwarePaginator
    {
        return $category->products()
            ->select('id', 'category_id', 'name', 'cover_image', 'price')
            // ->addSelect('categories.id as category_id', 'categories.name as category_name', 'categories.slug as category_slug')
            ->paginate();
    }
}
