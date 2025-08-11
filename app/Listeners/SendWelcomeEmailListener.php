<?php

namespace App\Listeners;

use App\Events\SubscriberVerifiedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeSubscriberMail;

class SendWelcomeEmailListener
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
    public function handle(SubscriberVerifiedEvent $event): void
    {
        Mail::to($event->subscriber->email)->send(new WelcomeSubscriberMail($event->subscriber));
    }
}
