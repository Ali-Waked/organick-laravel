<?php

namespace App\Listeners;

use App\Events\NewNewsletterSubscriptionEvent;
use App\Models\User;
use App\Notifications\ContactMessageNotification;
use App\Notifications\NewNewsletterSubscriptionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Enums\UserTypes;

class SendNewNewsletterSubscriptionNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewNewsletterSubscriptionEvent $event): void
    {
        Notification::send(User::where('type', UserTypes::Admin)->get(), new NewNewsletterSubscriptionNotification($event->subscriber));
    }
}
