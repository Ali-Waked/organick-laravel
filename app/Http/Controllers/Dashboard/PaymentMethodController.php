<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PaymentGatewayMethodStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentMethod;
use App\PaymentGateways\PaymentGatewayFactory;
use App\Services\ImageService;
use App\Services\PaymentMethodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function __construct(protected PaymentMethodService $paymentMethodService)
    {
        //
    }

    /**
     * Get All Payment Methods
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection
    {
        return $this->paymentMethodService->getAllPaymentMethods();
    }

    /**
     * Get Single Payment Methods
     * @param \App\Models\PaymentMethod $paymentMethod
     * @return \App\Models\PaymentMethod
     */
    public function show(PaymentMethod $paymentMethod): PaymentMethod
    {
        return $paymentMethod;
    }


    /**
     * Update Payment Methods Details
     * @param \App\Http\Requests\PaymentMethodRequest $request
     * @param \App\Models\PaymentMethod $paymentMethod
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod): JsonResponse
    {
        $this->paymentMethodService->updatePaymentMethod($paymentMethod, $request->validated());

        return Response::json([
            'message' => 'updated Successflly',
            'request' => $request->validated(),
        ]);
    }
}
