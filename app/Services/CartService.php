<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CartService
{
    public function getAllItems(): Collection
    {
        return Auth::user()->cartItems()->with([
            'product:id,name,cover_image,category_id,price',
            'product.category:id,name'
        ])->get();
    }

    public function store(array $data): CartItem|JsonResponse
    {
        $exists = Auth::user()->cartItems()->where('product_id', $data['product_id'])->exists();
        if ($exists) {
            return $this->updateProductQuantity($data['product_id'], $data['quantity']);
        }
        return Auth::guard('sanctum')->user()->cartItems()->create($data);
    }
    public function get(CartItem $cartItem): CartItem
    {
        return $cartItem;
    }
    public function update(int $productId, int $quantity): JsonResponse
    {
        // return $this->updateProductQuantity($productId, $quantity);
        $bool = Auth::user()->cartItems()->where('product_id', $productId)->update(['quantity' => $quantity]);
        return Response::json([
            'message' => 'updated ',
            'bool' => $bool
        ]);
    }
    public function empty(): mixed
    {
        return Auth::user()->cartItems()->delete();
    }
    public function removeItem(Cartitem $cartitem): bool|null
    {
        return $cartitem->delete();
    }

    public function totalPrice(User $user): float
    {
        $items = $user->cartItems()->with('product')->get();
        return $items->sum(function (Cart $item) {
            return $item->quantity * $item->product->price;
        });
    }

    public function items(User $user): Collection
    {
        return $user->cartItems()->with('product')->get();
    }
    protected function updateProductQuantity(int $productId, int $quantity): JsonResponse
    {
        $bool = Auth::user()->cartItems()->where('product_id', $productId)->increment('quantity', $quantity);
        return Response::json([
            'message' => 'updated ',
            'bool' => $bool
        ]);
    }
}
