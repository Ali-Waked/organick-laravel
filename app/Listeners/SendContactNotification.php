<?php

namespace App\Listeners;

use App\Enums\UserTypes;
use App\Events\ContactMessageSubmitted;
use App\Models\User;
use App\Notifications\ContactMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class SendContactNotification
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
    public function handle(ContactMessageSubmitted $event): void
    {
        // Notification::send(User::whereIn('type', [UserTypes::Admin->value, UserTypes::Moderator->value])->get(), new ContactMessageNotification($event->data));
        Notification::send(User::whereIn('type', [UserTypes::Admin->value, UserTypes::Moderator->value])->get(), new ContactMessageNotification($event->contactMessage));
    }
}
