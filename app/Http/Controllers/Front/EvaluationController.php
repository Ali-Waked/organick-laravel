<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluationRequest;
use App\Models\Evaluation;
use App\Models\Order;
use App\Models\Product;
use App\Services\EvaluationService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{

    public function __construct(
        protected EvaluationService $evaluationService,
        protected ProductService $productService
    ) {
        //
    }

    /**
     * Get Product Not Rating
     * @param int $orderId
     * @return \Illuminate\Support\Collection
     */
    public function show(int $orderId): Collection
    {
        return $this->productService->getUnreviewedProducts($orderId);
    }

    /**
     * Add Rating For Product
     * @param \App\Http\Requests\EvaluationRequest $request
     * @param int $orderId
     * @return \App\Models\Evaluation
     */
    public function store(EvaluationRequest $request, int $orderId): Evaluation
    {
        return $this->evaluationService->storeEvaluationForProduct($orderId, $request->validated());
    }
}
