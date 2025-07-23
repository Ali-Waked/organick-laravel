<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation)
    {
        return in_array($user->role, ['admin', 'moderator']);
    }

    // Allow only admin or moderator to send message
    public function update(User $user, Conversation $conversation)
    {
        return in_array($user->role, ['admin', 'moderator']);
    }
}
