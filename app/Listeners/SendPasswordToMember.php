<?php

namespace App\Listeners;

use App\Enums\UserTypes;
use App\Events\AddNewUser;
use App\Notifications\SendPasswordToMemberNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPasswordToMember
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
    public function handle(AddNewUser $event): void
    {
        $user = $event->user;
        if ($user->type == UserTypes::Driver || $user->type == UserType::Moderator)
            $user->notify(new SendPasswordToMemberNotification($event->password));
    }
}
