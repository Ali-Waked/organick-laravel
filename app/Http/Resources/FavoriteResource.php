<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product->id,
                'image' => $this->product->image,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'category' => [
                    'id' => $this->product->category->id,
                    'name' => $this->product->category->name,
                ],
            ],
            'created_at' => $this->created_at,
        ];
    }
}
