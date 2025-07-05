<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Address;
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
        info($order->payment->paymentMethod->name);
        return $order->load(['customer.billingAddress.city', 'shippingAddress.city', 'items.product', 'payment:paymentable_id,status,payment_method_id', 'payment.paymentMethod:id,name', 'driver:id,first_name,last_name,email,type']);
    }

    public function updateStatus(Order $order, OrderStatus $orderStatus): bool
    {
        info($orderStatus->value);
        return $order->update([
            'status' => $orderStatus,
        ]);
    }

    public function create(PaymentMethods $paymentMethod, array $data = []): Order
    {
        if ($paymentMethod->value == PaymentMethods::CashOnDelivery->value) {
            $data['status'] = OrderStatus::Processing;
        }
        return Order::create($data);
    }

    public function AddItems(Order $order, Collection $items): Collection
    {
        $orderItems = $items->map(fn(CartItem $item): array => [
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'product_name' => $item->product->name,
            'price' => $item->product->price,
        ])->toArray();

        return $order->items()->createMany($orderItems);
    }

    public function AddAddress(Order $order, array $data): Address
    {
        return $order->shippingAddress()->create($data);
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
            ->where('number', $id)
            ->with([
                'items',
                'shippingAddress.city:id,name,driver_price',
                'items.product:id,slug,name,price,cover_image',
                'items.product.category:id,slug',
                'payment:paymentable_type,paymentable_id,payment_method_id',
                'payment.paymentMethod:name,id'
            ])->first();
    }
}
