<?php

namespace App\Observers;

use App\Enums\CurrencyCode;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function creating(Order $order): void
    {
        $lastOrder = Order::latest()->first();
        $lastOrderNumber = $lastOrder?->number;
        $nowYear = now()->year;

        if ($nowYear != Str::substr($lastOrderNumber, 0, 4)) {
            $order->number = "{$nowYear}0001";
        } else {
            $order->number = $lastOrderNumber + 1;
        }
        $order->user_id = Auth::guard('sanctum')->id();
        if (!$order->currency) {
            $order->currency = CurrencyCode::USD;
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
