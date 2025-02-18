<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Events\SendVerifyEmailToSubscriber;
use App\Models\Blog;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class SubscriberService
{
    public function store(array $data): JsonResponse
    {
        $email = $data['email'];
        if ($user = Auth::guard('sanctum')->user()) {
            // If the user is already a subscriber
            if ($email === $user->email) {
                return Response::json([
                    'message' => 'Thank you for subscribing with your account!',
                ]);
            }
            $subscriber = Subscriber::firstOrCreate(
                ['email' => $user->email],
                ['email_verified_at' => now()]
            );
            return Response::json([
                'message' => 'Thank you for subscribing!',
                'subscriber' => $subscriber,
            ]);
        }
        $subscriber = Subscriber::firstOrCreate(
            ['email' => $email],
            ['email_verified_at' => null]
        );
        if ($subscriber->email_verified_at) {
            return Response::json([
                'message' => 'Thank you for subscribing',
            ]);
        }
        SendVerifyEmailToSubscriber::dispatch($subscriber);
        return Response::json([
            'message' => 'Thank you for subscribing! Please check your email to verify your subscription.',
            'Subscriber' => $subscriber,
        ], 201);
    }

    public function verify(string $email): void
    {
        Subscriber::where('email', $email)->update([
            'email_verified_at' => now(),
        ]);
    }

    public function changeStatus(SubscriptionStatus $subscriptionStatus): void
    {
        Subscriber::where('email', Auth::user()->email)->update([
            'status' => $subscriptionStatus->value,
        ]);
    }
}
