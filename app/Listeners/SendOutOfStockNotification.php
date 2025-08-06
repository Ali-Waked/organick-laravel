<?php

namespace App\Listeners;

use App\Events\OutOfStockEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Enums\UserTypes;
use App\Notifications\OutOfStockNotification;

class SendOutOfStockNotification
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
    public function handle(OutOfStockEvent $event): void
    {
        $admins = User::where('type', UserTypes::Admin)->get();

        Notification::send($admins, new OutOfStockNotification($event->product));
    }
}
