<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Mail\RatingReminderMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRatingReminder implements ShouldQueue
{
    use Queueable;

    /**
     * When Order Status is Completed After 3 days Send Reminder Message For Rating Product
     * @return void
     */
    public function handle(): void
    {
        $orders = Order::where('status', OrderStatus::Completed)
            ->whereDate('created_at', '<', now()->subDays(3))
            ->whereDate('created_at', '>',)
            ->get();

        foreach ($orders as $order) {
            Mail::to($order->user)->send(new RatingReminderMail($order));
        }
    }
}
