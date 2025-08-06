<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;

class SendOrderNotification
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
    public function handle(OrderCreated $event): void
    {
        User::whereIn('type', ['admin', 'moderator', 'driver'])
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new OrderCreatedNotification($event->order));
            });
    }
}
