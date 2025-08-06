<?php

namespace App\Listeners;

use App\Events\RateProductEvent;
use App\Notifications\RateProductNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Enums\UserTypes;

class SendRateProductNotification
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
    public function handle(RateProductEvent $event): void
    {
        $admins = User::where('type', UserTypes::Admin)->get();

        Notification::send($admins, new RateProductNotification($event->customer, $event->product, $event->feedback));
    }
}
