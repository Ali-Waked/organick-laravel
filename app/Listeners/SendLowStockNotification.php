<?php

namespace App\Listeners;

use App\Events\LowStockEvent;
use App\Notifications\LowStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Enums\UserTypes;

class SendLowStockNotification
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
    public function handle(LowStockEvent $event): void
    {
        $admins = User::where('type', UserTypes::Admin)->get();

        Notification::send($admins, new LowStockNotification($event->product));
    }
}
