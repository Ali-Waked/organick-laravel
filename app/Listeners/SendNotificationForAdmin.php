<?php

namespace App\Listeners;

use App\Events\NewCustomerRegisteredEvent;
use App\Notifications\CustomerRegisteredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Enums\UserTypes;


class SendNotificationForAdmin
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
    public function handle(NewCustomerRegisteredEvent $event): void
    {
        $admins = User::where('type', UserTypes::Admin)->get();

        Notification::send($admins, new CustomerRegisteredNotification($event->customer));
    }
}
