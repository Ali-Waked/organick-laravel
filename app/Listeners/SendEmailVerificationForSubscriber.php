<?php

namespace App\Listeners;

use App\Events\SendVerifyEmailToSubscriber;
use App\Mail\VeryfiyEmailForSubscriper;
use App\Notifications\SubscriptionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationForSubscriber
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the subscriber.
     */
    public function handle(SendVerifyEmailToSubscriber $event): void
    {
        Mail::to($event->subscriber->email)->send(new VeryfiyEmailForSubscriper($event->subscriber->email, $event->name));
    }
}
