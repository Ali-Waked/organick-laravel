<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\ConversationStatus;

class ConversationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversation = Conversation::where('customer_id', $user->id)->first();

        return response()->json($conversation);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $conversation = Conversation::firstOrCreate(
            ['customer_id' => $user->id],
            [
                'status' => ConversationStatus::OPEN->value,
                'last_customer_message_at' => now(),
            ]
        );

        return response()->json($conversation);
    }
}