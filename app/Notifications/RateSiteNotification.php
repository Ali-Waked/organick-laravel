<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\SiteFeedback;
use App\Enums\NotificationType;

class RateSiteNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $customer, public SiteFeedback $feedback)
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
            'message' => "The customer {$this->customer->full_name} evaluated the Site",
            'type_notify' => NotificationType::SITE_RATED->value,
            'feedback' => $this->feedback,
            'customer' => $this->user,
        ];
    }

    public function broadcastAs(): string
    {
        return 'rate-site';
    }
}
