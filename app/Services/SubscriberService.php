<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Events\NewNewsletterSubscriptionEvent;
use App\Events\SendVerifyEmailToSubscriber;
use App\Models\Blog;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Events\SubscriberVerifiedEvent;

class SubscriberService
{
    public function store(array $data): JsonResponse
    {
        $email = $data['email'];
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            if ($email === $user->email) {
                $subscriber = Subscriber::firstOrCreate(
                    ['email' => $user->email],
                    [
                        'user_id' => $user->id,
                        'is_subscribed' => true,
                        'email_verified_at' => now(),
                        'verification_token' => null
                    ]
                );

                return Response::json([
                    'message' => 'Thank you for subscribing with your account!',
                    'subscriber' => $subscriber,
                ]);
            }
            return Response::json([
                'message' => 'You can only subscribe with your account email.',
            ], 422);
        }
        $subscriber = Subscriber::firstOrNew(['email' => $email]);
        info("Subscriber: " . $subscriber->email);

        if ($subscriber->exists && $subscriber->email_verified_at) {
            return Response::json([
                'message' => 'You are already subscribed!',
            ]);
        }

        $subscriber->is_subscribed = false;
        $subscriber->verification_token = Str::uuid();
        $subscriber->email_verified_at = null;
        $subscriber->save();

        SendVerifyEmailToSubscriber::dispatch($subscriber);
        event(new NewNewsletterSubscriptionEvent($subscriber));

        return Response::json([
            'message' => 'Thank you for subscribing! Please check your email to verify your subscription.',
            'subscriber' => $subscriber,
        ], 201);

    }

    public function verify(string $token): JsonResponse
    {
        $subscriber = Subscriber::where('verification_token', $token)->firstOrFail();
        $subscriber->update([
            'email_verified_at' => now(),
            'verification_token' => null,
            'is_subscribed' => true,
        ]);

        event(new SubscriberVerifiedEvent($subscriber));

        return Response::json([
            'message' => 'Your subscription has been verified successfully!',
        ]);
    }

    public function changeStatus(Subscriber $subscriber): void
    {
        $subscriber->update([
            'is_subscribed' => !$subscriber->is_subscribed,
        ]);
    }
}
