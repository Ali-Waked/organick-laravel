<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderAddress;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function paginatedOrders(?string $filter = ''): LengthAwarePaginator
    {
        return Order::with('customer:id,email')->filter(json_decode($filter))->paginate();
    }

    public function getOrderWithRelation(Order $order): Order
    {
        return $order->load(['customer', 'address', 'items.product', 'payment:paymentable_id,status']);
    }

    public function updateStatus(Order $order, OrderStatus $orderStatus): bool
    {
        return $order->update([
            'status' => $orderStatus->value,
        ]);
    }

    public function create(PaymentMethods $paymentMethod, array $data): Order
    {
        if ($paymentMethod->value == PaymentMethods::CashOnDelivery->value) {
            $data['status'] = OrderStatus::Processing;
        }
        return Order::create($data);
    }

    public  function AddItems(Order $order, Collection $items): Collection
    {
        $orderItems = $items->map(fn(Cart $item): array => [
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'product_name' => $item->product->name,
            'price' => $item->product->price,
        ])->toArray();

        return $order->items()->createMany($orderItems);
    }

    public function AddAddress(Order $order, array $data): OrderAddress
    {
        return $order->address()->create($data);
    }

    public function getAuthOrder(): LengthAwarePaginator
    {
        return Order::where('user_id', Auth::guard('sanctum')->id())
            ->withCount('items')
            ->paginate();
    }

    public function getOrderForUser(int $id): Order
    {
        return Auth::user()->orders()
            ->where('order_id', $id)
            ->with([
                'items',
                'items.product:id,slug,name,price',
                'items.product.category:id,slug'
            ])->first();
    }
}
