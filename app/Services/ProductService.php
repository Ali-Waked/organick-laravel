<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductService
{

    public function __construct(protected OrderService $orderService)
    {
        //
    }

    /**
     * Get All Product With Category Id,name,image
     * @param mixed $filters
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProductsWithFilters(?string $filters): AnonymousResourceCollection
    {
        $products = Product::with('category:id,name,cover_image')->filter(json_decode($filters))->paginate();
        return ProductResource::collection($products);
    }


    /**
     * Create New Product
     * @param array $data
     * @return \App\Models\Product
     */
    public function createProduct(array $data): Product
    {
        $data['cover_image'] = Product::__callStatic('uploadImage', [$data['image'], Product::FOLDER]);
        return Product::create($data);
    }

    /**
     * Get Product With Category And User Created this Product And related tags
     * @param \App\Models\Product $product
     * @return \App\Models\Product
     */
    public function getProductWithRelations(Product $product): Product
    {
        return $product->append(['image', 'AverageRating', 'total_requests', 'current_discount', 'FinalPrice'])
            ->load(['category:id,name,slug', 'tags:name', 'feedbacks.customer:id,email,first_name,last_name,avatar']);
        // ->loadAvg('evaluations as rating', 'rating')
        // ->loadSum('orderItems as total_sold', 'quantity');
    }


    /**
     * Update Product
     * @param \App\Models\Product $product
     * @param array $data
     * @return bool
     */
    public function updateProduct(Product $product, array $data): bool
    {
        $cover_image = $product->cover_image;

        if (isset($data['image'])) {
            $data['cover_image'] = $product->uploadImage($data['image'], Product::FOLDER);
        }

        $isUpdated = $product->update($data);

        if ($cover_image && isset($data['image'])) {
            $product->removeImage($cover_image);
        }
        return $isUpdated;
    }

    /**
     * Delete Product
     * @param \App\Models\Product $product
     * @return bool
     */
    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }

    /**
     * Get All Product In Trash
     * @param string $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getProductsFromTrash(string $filter): LengthAwarePaginator
    {
        return Product::onlyTrashed()->with('category:id,name,cover_image')->filter(json_decode($filter))->paginate();
    }

    /**
     * Restore Product
     * @param int $id
     * @return \App\Models\Product
     */
    public function restoreProductFromTrash(int $id): Product
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->restore();

        return $product;
    }

    /**
     * Delete Product Forever
     * @param int $id
     * @return \App\Models\Product
     */
    public function deleteProductForever(int $id): Product
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->forceDelete();

        return $product;
    }

    public function getUnreviewedProducts(int $orderId): Collection
    {
        $order = $this->orderService->getOrderForUser($orderId);
        if (!$order) {
            throw new \Exception("Order not found or not authorized.");
        }

        return Product::whereRelation('orders', 'id', '=', $order->id)
            ->whereDoesntHave('userReviews')
            ->get();
    }
}
