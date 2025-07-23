<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ConversationStatus;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ConversationController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $conversations = Conversation::with('customer')
            ->when($user->isAdmin, function ($q) {
                $q->where('status', ConversationStatus::OPEN->value);
            })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn($conv) => [
                'id' => $conv->id,
                'customer_name' => $conv->customer->fullName,
                'last_message_excerpt' => optional($conv->messages()->latest()->first())->message,
                'avatar' => $conv->customer->avatar,
                'updated_at' => $conv->updated_at,
            ]);
        return response()->json($conversations);
    }

    // Get messages of a conversation
    public function messages(Conversation $conversation)
    {
        // $this->authorize('view', $conversation);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderByDesc('created_at')
            ->paginate(10);

        $messages->getCollection()->transform(function ($msg) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->id == Auth::id() ? 'You' : $msg->sender->fullName,
                'created_at' => $msg->created_at,
            ];
        });
        $reversedCollection = $messages->getCollection()->sortBy('created_at')->values();

        $messages->setCollection($reversedCollection);
        return response()->json($messages);
    }

    // Send a message as moderator/admin
    public function sendMessage(Request $request, Conversation $conversation)
    {
        // $this->authorize('update', $conversation);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        if (is_null($conversation->receiver_id)) {
            $conversation->receiver_id = auth()->id();
            $conversation->receiver_role = auth()->user()->type;
            $conversation->save();
        }

        $message = new Message();
        $message->conversation_id = $conversation->id;
        $message->sender_id = Auth::id();
        $message->message = $request->message;
        $message->save();

        // Update conversation's updated_at
        // $conversation->touch();

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent',
        ]);
    }
}
