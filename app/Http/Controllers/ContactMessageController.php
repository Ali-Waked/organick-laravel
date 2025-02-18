<?php

namespace App\Http\Controllers;

use App\Events\ContactMessageSubmitted;
use App\Http\Requests\ContactMessageRequest;
use App\Mail\SendContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class ContactMessageController extends Controller
{
    public function __invoke(ContactMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        Mail::queue(new SendContactMessage($data));
        ContactMessageSubmitted::dispatch($data);
        return Response::json([
            'message' => 'success to send message',
        ]);
    }
}
