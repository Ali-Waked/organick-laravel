<?php

namespace App\Listeners;

use App\Events\NewCustomerRegisteredEvent;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelecomeMessageForCustomer
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
        $event->customer->notify(new WelcomeNotification());
    }
}
