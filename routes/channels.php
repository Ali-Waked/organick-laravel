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
