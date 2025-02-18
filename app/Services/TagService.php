<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Tag;

class TagService
{

    public function createOrUpdateTags(Product $product, string $tags): void
    {
        $requestTags = explode(',', $tags);
        $tagData = collect($requestTags)->map(fn($tag) => ['name' => $tag]);
        Tag::upsert($tagData->toArray(), ['name']);
        $tags = Tag::whereIn('name', $requestTags)->pluck('id');
        $product->tags()->sync($tags);
    }
}
