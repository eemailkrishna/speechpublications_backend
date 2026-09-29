<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastOn(): array
    {
        return [new Channel('conversation.' . $this->message->conversation_id)];
    }

    public function broadcastWith(): array
    {
        $sender = $this->message->sender;

        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'text' => $this->message->text,
            'image' => $this->message->image,
            'status' => $this->message->status,
            'created_at' => $this->message->created_at->toISOString(),
            'sender' => $sender ? [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->profile_photo,
            ] : null,
        ];
    }
}
