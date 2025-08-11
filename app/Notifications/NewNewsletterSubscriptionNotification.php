<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Subscriber;
use App\Enums\NotificationType;

class NewNewsletterSubscriptionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Subscriber $subscriber)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "New newsletter subscription from {$this->subscriber->email}",
            'type_notify' => NotificationType::NEWSLETTER_SUBSCRIBED->value,
            'subscriber' => $this->subscriber,
        ];
    }
    public function broadcastAs(): string
    {
        return 'new-subscriber';
    }

}
