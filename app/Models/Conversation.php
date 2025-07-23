<?php

namespace App\Models;

use App\Enums\ConversationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'receiver_id',
        'status',
        'last_moderator_reply_at',
        'receiver_role',
        'last_moderator_reply_at',
    ];

    protected function casts(): array
    {
        return [
            'last_customer_message_at' => 'datetime',
            'last_moderator_reply_at' => 'datetime',
            'status' => ConversationStatus::class,
        ];
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
