<?php

namespace App\Listeners;

use App\Events\RateSiteEvent;
use App\Notifications\RateSiteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Enums\UserTypes;

class SendRateSiteNotification
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
    public function handle(RateSiteEvent $event): void
    {
        $admins = User::where('type', UserTypes::Admin)->get();

        Notification::send($admins, new RateSiteNotification($event->customer, $event->feedback));
    }
}
