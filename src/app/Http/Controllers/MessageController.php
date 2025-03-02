<?php

namespace App\Http\Controllers;

use App\Events\NewMessageEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'login' => 'required|string',
            'content' => 'required|string',
        ]);

        Log::info('Creating new message', ['login' => $validated['login'], 'content' => $validated['content']]);

        $user = User::firstOrCreate(['login' => $validated['login']]);
        $message = $user->messages()->create(['content' => $validated['content']]);

        Log::info('Message created, dispatching event', ['message_id' => $message->id]);
        broadcast(new NewMessageEvent($message));
        Log::info('Event dispatched');

        return response()->json([
            'message' => $message->load('user'),
            'status' => 'Message created successfully',
        ]);
    }
} 