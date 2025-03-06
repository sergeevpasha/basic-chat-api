<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\NewMessageEvent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Broadcast;

class MessageService
{
    /**
     * Get all messages with their associated users.
     *
     * @return Collection
     */
    public function getAllMessages(): Collection
    {
        return Message::with('user')->orderBy('created_at')->get();
    }

    /**
     * Create a new message.
     *
     * @param string $login The user's login
     * @param string $messageContent The message content
     * @return Message The created message with user relation loaded
     */
    public function createMessage(string $login, string $messageContent): Message
    {
        $user = User::where('login', $login)->firstOrFail();

        $message = $user->messages()->create([
            'content' => $messageContent,
        ]);

        $message->load('user');

        // Broadcast the new message event
        Broadcast::event(new NewMessageEvent($message));

        return $message;
    }
}
