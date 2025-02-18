<?php

namespace App\Events;

use App\Models\Order;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Order $order)
    {
        // dd($order);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-notifications'),
        ];
    }
    public function broadcastAs(): string
    {
        return 'order-created';
    }
    public function broadcastWith(): array
    {
        return [
            'order' => [
                'id' => $this->order->id,
                'number' => $this->order->number,
                'total' => $this->order->items->sum(''),
                'currency' => $this->order->currency,
            ],
            'customer' => [
                'id' => $this->order->customer->id,
                'email' => $this->order->customer->email,
                'first_name' => $this->order->customer->first_name,
                'last_name' => $this->order->customer->last_name,
            ],
        ];
    }
}
