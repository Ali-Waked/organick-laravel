<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveExpiredPendingOrders implements ShouldQueue
{
    use Queueable;

    /**
     * Remove Orders Has Status Pending After 7 days
     * @return void
     */
    public function handle(): void
    {
        $orders = Order::where('status', OrderStatus::Pending)
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->get();

        foreach ($orders as $order) {
            $order->delete();
        }
    }
}
