<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Tag;
use App\Services\ImageService;
use App\Services\ProductService;
use App\Services\TagService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected TagService $tagService
    ) {
        //
    }

    /**
     * Get All Products
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        Gate::authorize('view.product');
        return $this->productService->getProductsWithFilters($request->filter);
    }

    /**
     * Create New Product With Tags
     * @param \App\Http\Requests\ProductRequest $request
     * @return \App\Models\Product
     */
    public function store(ProductRequest $request): Product
    {
        Gate::authorize('create.product');

        DB::beginTransaction();

        try {
            $product = $this->productService->createProduct($request->safe()->except(['tags']));
            // return $request->tags;
            if ($request->post('tags')) {
                $this->tagService->createOrUpdateTags($product, $request->safe()->only('tags')['tags']);
            }

            DB::commit();

            return $product;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }


    /**
     * Get Single Product
     * @param \App\Models\Product $product
     * @return \App\Models\Product
     */
    public function show(Product $product): Product
    {
        Gate::authorize('show.product');
        return $this->productService->getProductWithRelations($product);
    }

    /**
     * Update Product With Tags
     * @param \App\Http\Requests\ProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        Gate::authorize('update.product');
        DB::beginTransaction();

        try {

            $this->productService->updateProduct($product, $request->safe()->except(['tags']));

            if ($request->post('tags')) {
                $this->tagService->createOrUpdateTags($product, $request->safe()->only(['tags'])['tags']);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();

            throw $e;
        }

        return Response::json([
            'message' => 'Updated Product Successflly',
        ]);
    }

    /**
     * Delete Product (translate product to trash)
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        Gate::authorize('delete.product');

        $this->productService->deleteProduct($product);
        return Response::json([
            'message' => 'deleted successflly',
        ]);
    }

    /**
     * Get All Product From Trash
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function trash(Request $request): LengthAwarePaginator
    {
        Gate::authorize('view.products.trash');
        $products = $this->productService->getProductsFromTrash($request->filter);

        return $products;
    }

    /**
     * Restore Product From Trash
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        Gate::authorize('restore.product');
        $product = $this->productService->restoreProductFromTrash($id);

        return Response::json([
            'message' => "Restore {$product->name} Successflly",
        ]);
    }

    /**
     * Delete Product From Trash Forever
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete(int $id): JsonResponse
    {
        Gate::authorize('deleteforever.product');
        $product = $this->productService->deleteProductForever($id);

        return Response::json([
            'message' => "Delete Product {$product->name} successflly",
        ]);
    }

    /**
     * Get All Products For Select
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): Collection
    {
        Gate::authorize('view.product');
        return Product::where('is_active', true)->select(['id', 'name', 'cover_image'])->get();

    }
}
