<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        return Subscriber::with('user')->filter(json_decode($request->filter))->paginate();
    }

    public function show(Subscriber $subscriber): Subscriber
    {
        return $subscriber->load(['user']);
    }

    public function update(Subscriber $subscriber): JsonResponse
    {
        $subscriber->update([
            'is_subscribed' => !$subscriber,
        ]);
        return response()->json([
            'message' => $subscriber->is_subscribed ? 'Subscription activated' : 'Subscription deactivated',
            'subscriber' => $subscriber,
        ]);
    }
}
