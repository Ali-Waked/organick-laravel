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
        $products->getCollection()->each->append(['isFavorite', 'averageRating']);
        return $products;
    }
    public function show(Product $product): Product
    {
        return $product->load(['category:id,name', 'feedbacks.customer'])->append(['isFavorite', 'canRate', 'averageRating']);
    }
    public function getRelatedProducts(Request $request, Product $product): LengthAwarePaginator
    {
        $tags = $product->tags()->pluck('tag_id');
        $request->validate([
            'page' => 'integer|min:1|max:100',
        ]);

        $similarProducts = Product::where('id', '<>', $product->id)->where(function ($query) use ($product, $tags) {
            $query->where('category_id', $product->category_id)
                ->orWhereHas('tags', function ($q) use ($tags) {
                    $q->whereIn('tag_id', $tags);
                });
        })
            ->select('id', 'category_id', 'name', 'cover_image', 'price')
            ->with(['category:id,name,slug', 'tags:id,name'])
            ->paginate($request->query('page', 12));
        $similarProducts->getCollection()->each->append(['isFavorite', 'averageRating']);
        return $similarProducts;
    }
}
