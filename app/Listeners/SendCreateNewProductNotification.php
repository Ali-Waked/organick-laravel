<?php

namespace App\Listeners;

use App\Events\AddNewProductEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCreateNewProductNotification
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
    public function handle(AddNewProductEvent $event): void
    {
        //
    }
}
