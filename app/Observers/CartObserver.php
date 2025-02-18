<?php

namespace App\Observers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartObserver
{
    /**
     * Handle the Cart "created" event.
     */
    public function creating(Cart $cart): void
    {
        $cart->user_id = Auth::guard('sanctum')->id();
    }

    /**
     * Handle the Cart "updated" event.
     */
    public function updated(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        //
    }
}
