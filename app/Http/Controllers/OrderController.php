<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PayMethods;
use App\Models\Cart;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService)
    {
        //
    }

    /**
     * Get Authentication Orders
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return $this->orderService->getAuthOrder();
    }

    /**
     * Get User Order
     * @param int $id
     * @return \App\Models\Order|\Illuminate\Http\JsonResponse
     */
    public function show(int $id): Order|JsonResponse
    {
        $order = $this->orderService->getOrderForUser($id);
        if ($order) {
            return $order;
        }
        return Response::json([
            'message' => 'order not found',
        ]);
    }
}
