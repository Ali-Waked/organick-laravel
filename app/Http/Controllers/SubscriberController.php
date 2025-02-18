<?php

namespace App\Http\Controllers;

use App\Enums\SubscriptionStatus;
use App\Events\SendVerifyEmailToSubscriber;
use App\Http\Requests\SubscribeRequest;
use App\Models\Subscriber;
use App\Services\SubscriberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class SubscriberController extends Controller
{

    public function __construct(protected SubscriberService $subscriberService)
    {
        //
    }

    /**
     * Subscriber for Newsletter
     * @param \App\Http\Requests\SubscribeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SubscribeRequest $request): JsonResponse
    {
        return $this->subscriberService->store($request->validated());
    }

    /**
     * Verfiy Email For Subscriber To Send Newsletter
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request): RedirectResponse
    {
        $this->subscriberService->verify($request->email);
        return Redirect::to(Config::get('app.front-url'));
    }

    /**
     * Chage Subscriber Status
     * @param \App\Enums\SubscriptionStatus $subscriptionStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SubscriptionStatus $subscriptionStatus): JsonResponse
    {
        $this->subscriberService->changeStatus($subscriptionStatus);
        return Response::json([
            'message' => "Subscription status updated to {$subscriptionStatus->value} successfully",
        ]);
    }
}
