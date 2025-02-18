<?php

namespace App\Listeners;

use App\Events\BlogPublished;
use App\Mail\NewBlogPublished;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailForSubscriberInNewsletter
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
        $subscribers = Subscriber::acceptMessage()->get();
        Mail::to($subscribers)->send(new NewBlogPublished($event->blog));
    }
}
