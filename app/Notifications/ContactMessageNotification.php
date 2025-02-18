<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class ContactMessageNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public array $data)
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
        return ['database',  'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'subject' => $this->data['subject'],
            'message' => $this->data['message'],
            'created_at' => $this->data['created_at'],
            'icon' => 'mdi-email-outline',
        ];
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.notification')];
    }
    public function broadcastAs(): string
    {
        return 'new-contact-us-message';
    }
}
