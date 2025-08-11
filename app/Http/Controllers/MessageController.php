<?php
namespace App\Http\Controllers;

use App\Events\NewChatMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Enums\UserTypes;

class MessageController extends Controller
{
    public function index($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Optional: authorize access to this conversation
        return response()->json(
            $conversation->messages()->with('sender:id,first_name,last_name,type')->get()->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->type == UserTypes::Customer ? $msg->sender->fullName : 'Support Team',
                    'is_read' => $msg->is_read,
                    'created_at' => $msg->created_at,
                ];
            })
        );
    }

    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);

        // Save message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        // Update conversation timestamps
        if ($user->id === $conversation->customer_id) {
            $conversation->last_customer_message_at = now();
        } else {
            $conversation->last_moderator_reply_at = now();

            // If no assigned receiver yet or the old one is inactive, assign this sender
            if (!$conversation->receiver_id || $conversation->receiver_id !== $user->id) {
                $conversation->receiver_id = $user->id;
                $conversation->receiver_role = $user->role; // assuming role is in users table
            }
        }

        $conversation->save();
        event(new MessageSent($message));
        if ($user->type->value == UserTypes::Customer->value) {
            event(new NewChatMessageEvent($user, $message, $conversationId));
        }


        return response()->json([
            'message' => 'Message sent successfully.',
            'data' => [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $user->id,
                'sender_name' => $user->type->value == UserType::customer->value ? $user->full_name : 'Support Team',
                'created_at' => $message->created_at,
            ]
        ]);
    }


}