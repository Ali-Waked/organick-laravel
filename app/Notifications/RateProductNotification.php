<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;
use App\Models\User;
use App\Enums\NotificationType;
use App\Models\Feedback;

class RateProductNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $customer, public Product $product, public Feedback $feedback)
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
            'message' => "The customer {$this->customer->full_name} evaluated the product \"${$this->product->name}\"",
            'type_notify' => NotificationType::PRODUCT_RATED->value,
            'product' => $this->product,
            'customer' => $this->customer,
            'feedback' => $this->feedback,
        ];
    }
    public function broadcastAs(): string
    {
        return 'rate-product';
    }
}
