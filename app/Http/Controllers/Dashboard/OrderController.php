<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
        //
    }

    /**
     * Get Orders
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        return $this->orderService->paginatedOrders($request->filter);
    }

    /**
     * Get Order With Relation
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    public function show(Order $order): Order
    {
        return $this->orderService->getOrderWithRelation($order);
    }

    /**
     * Update Order Status
     * @param \App\Models\Order $order
     * @param \App\Enums\OrderStatus $orderStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Order $order, OrderStatus $orderStatus): JsonResponse
    {
        $this->orderService->updateStatus($order, $orderStatus);
        return Response::json([
            'message' => "update order status to {$orderStatus->value}",
        ]);
    }
}
