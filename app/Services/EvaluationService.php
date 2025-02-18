<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EvaluationService
{

    public function __construct(protected OrderService $orderService)
    {
        //
    }
    public function storeEvaluationForProduct(int $orderId, array $data): Evaluation
    {
        $order = $this->orderService->getOrderForUser($orderId);
        if (!$order) {
            throw new \Exception("Order not found or not authorized.");
        }

        return Evaluation::create([
            'user_id' => Auth::id(),
            'comment' => $data['comment'],
            'rating' => $data['rating'],
            'assessable_type' => 'product',
            'assessable_id' => $data['productId'],
        ]);
    }
}
