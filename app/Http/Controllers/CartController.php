<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
        //
    }

    /**
     * Return All Items In Cart
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection
    {
        return $this->cartService->getAllItems();
    }

    /**
     * Add Item To Cart
     * @param \App\Http\Requests\CartRequest $request
     * @return \App\Models\Cart|\Illuminate\Http\JsonResponse
     */
    public function store(CartRequest $request): CartItem|JsonResponse
    {
        return $this->cartService->store($request->validated());
    }

    /**
     * Get Item From Cart
     * @param \App\Models\CartItem $CartItem
     * @return \App\Models\CartItem
     */
    public function show(CartItem $cartItem): CartItem
    {
        return $this->cartService->get($cartItem);
    }

    /**
     * Update Item Quntity In Cart
     * @param \App\Http\Requests\CartRequest $request
     * @param \App\Models\Cart $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CartRequest $request, CartItem $cartItem): JsonResponse
    {
        return $this->cartService->update($cartItem->product_id, $request->safe()->only('quantity')['quantity']);
    }

    /**
     * Empty Cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(): JsonResponse
    {
        $this->cartService->empty();
        return Response::json([
            'message' => 'remove all items from carts successflly',
        ]);
    }

    /**
     * Remove Item From Cart
     * @param \App\Models\Cart $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItem(CartItem $cartItem): JsonResponse
    {
        $this->cartService->removeItem($cartItem);

        return Response::json([
            'message' => "remove {$cartItem->product->name} from cart successflly",
        ]);
    }
}
