<?php

namespace App\Listeners;

use App\Events\NewChatMessageEvent;
use App\Notifications\NewChatMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;

class SendNewChatMessageNotification
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
    public function handle(NewChatMessageEvent $event): void
    {
        User::whereIn('type', ['admin', 'moderator', 'driver'])
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new NewChatMessageNotification($event->customer, $event->message, $event->conversationId));
            });
    }
}
