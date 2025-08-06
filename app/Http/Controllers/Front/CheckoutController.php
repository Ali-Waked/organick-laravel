<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Enums\PayMethods;
use App\Events\OrderCreated;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\PaymentGateways\PaymentGatewayFactory;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\Intl\Countries;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService,
        protected PaymentService $paymentService
    ) {
        //
    }

    /**
     * Checkout
     * @param \App\Http\Requests\CheckoutRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CheckoutRequest $request): JsonResponse
    {

        $user = Auth::guard('sanctum')->user();

        $items = $this->cartService->items($user);

        DB::beginTransaction();
        try {

            // create order
            $order = $this->orderService->create(
                PaymentMethods::from($request->validated('pay_method')),
                $request->only('currency'),
            );

            // add items for order
            $orderItems = $this->orderService->AddItems($order, $items);

            // add address for order
            $this->orderService->AddAddress($order, $request->safe()->except(['currency', 'pay_method']));

            // get gateway for order
            $gateway = PaymentGatewayFactory::create($request->post('pay_method'));
            $data = $gateway->create($order, $user);

            $this->paymentService->create($order, $data);

            DB::commit();

            OrderCreated::dispatch($order->load(['customer:id,first_name,last_name,email,avatar,created_at', 'shippingAddress.city']));

            return Response::json([
                'message' => 'created order successfly',
                'payment_method_details' => $data['payment_method_details'],
                'order' => $order,
                'items' => $orderItems,
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show(int $id): Order|JsonResponse
    {
        $order = Order::with([
            'items',
            'items.product:id,slug,name,price',
            'items.product.category:id,slug'
        ])->where([
                    'id' => $id,
                    'user_id' => Auth::guard('sanctum')->id(),
                ])->first();
        return $order ? $order : Response::json([
            'message' => 'order not found',
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => ['sometimes', 'required', Rule::in(OrderStatus::Canceled, OrderStatus::Refunded)],
            'pay_method' => ['sometimes', 'required', Rule::enum(PayMethods::class)],
        ]);
        $order = Order::with([
            'items',
            'items.product:id,slug,name,price',
            'items.product.category:id,slug'
        ])->where([
                    'id' => $id,
                    'user_id' => Auth::guard('sanctum')->id(),
                ])->first();
        if ($order->status == OrderStatus::Pending) {
            $status = $request->post('status', $order->status);
            $method = $request->post('pay_method', $order->method);
            $order->update([
                'status' => $status,
                'method' => $method,
            ]);
            return Response::json([
                'message' => 'updated order',
            ]);
        }
        return Response::json([
            'message' => 'can not updated',
        ]);
    }
}
