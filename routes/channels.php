<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin.notifications', function ($user) {
    return $user->email === 'ali.waked@gmail.com';
});
Broadcast::channel('category.created', function ($user) {
    // return $user->email === 'ali.waked@gmail.com';
    return true;
});

Broadcast::channel('conversation.{id}', function ($user, $id) {
    return $user->id === \App\Models\Conversation::find($id)?->customer_id
        || $user->id === \App\Models\Conversation::find($id)?->receiver_id
        || $user->isAdmin();
});
