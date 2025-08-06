<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;
use App\Enums\NotificationType;

class OutOfStockNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Product $product)
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

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "The product {$this->product->name} is now out of stock.",
            'type_notify' => NotificationType::PRODUCT_OUT_OF_STOCK->value,
            'product' => $this->product,
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.out_of_stock';
    }
}
