<?php

namespace App\Listeners;

use App\Enums\UserTypes;
use App\Events\BlogPublished;
use App\Models\User;
use App\Notifications\CreateNewBlogNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationForAdminAndMemberAndSupportedTeam
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
    public function handle(BlogPublished $event): void
    {
        $users = User::where('type', '<>', UserTypes::Customer)->get();
        Notification::send($users, new CreateNewBlogNotification($event->blog));
    }
}
