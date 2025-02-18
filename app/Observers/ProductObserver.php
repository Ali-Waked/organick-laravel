<?php

namespace App\Observers;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductObserver
{

    public function __construct(protected ImageService $imageService) {}
    /**
     * Handle the Product "created" event.
     */
    public function saving(Product $product): void
    {
        $slug = Str::slug($product->name);
        $count = Product::where('slug', 'LIKE', "$slug%")->count();
        $slug .= '-' . $count + 1;
        $product->slug = $slug;
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->imageService->removeImage($product->cover_image);
    }
}
