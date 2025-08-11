<?php

namespace App\Http\Controllers;

use App\Events\ContactMessageSubmitted;
use App\Http\Requests\ContactMessageRequest;
use App\Mail\SendContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        return ContactMessage::with('reply:id,first_name,last_name,email,avatar')->filter(json_decode($request->filter))->paginate();
    }

    public function show(ContactMessage $contactMessage): ContactMessage
    {
        return $contactMessage->load(['reply']);
    }
    public function store(ContactMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        // ;
        $contactMessage = ContactMessage::create($data);
        ContactMessageSubmitted::dispatch($contactMessage);
        return Response::json([
            'message' => 'success to send message',
        ]);
    }

    public function send(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);
        $contactMessage->update([
            'reply_message' => $request->message,
            'reply_id' => $request->user()->id,
            'replyed_at' => now(),
        ]);

        Mail::queue(new SendContactMessage($contactMessage));

        return response()->json([
            'message' => '',
        ]);
    }


}
