<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Message;
use App\Enums\NotificationType;

class NewChatMessageNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $customer, public Message $message, public int $conversationId)
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
            'message' => "New chat message from customer {$this->customer->full_name}",
            'type_notify' => NotificationType::CHAT_MESSAGE->value,
            'conversation_id' => $this->conversationId,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->full_name,
                'email' => $this->customer->email,
            ],
            'message' => $this->message,
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat-message';
    }
}
