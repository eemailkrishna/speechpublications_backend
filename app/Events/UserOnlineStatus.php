<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOnlineStatus implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public bool $isOnline;

    public function __construct(User $user, bool $isOnline)
    {
        $this->user = $user;
        $this->isOnline = $isOnline;
    }

    public function broadcastAs(): string
    {
        return 'user.online.status';
    }

    public function broadcastOn(): array
    {
        return [new Channel('user.status.' . $this->user->id)];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'is_online' => $this->isOnline,
            'last_seen_at' => $this->user->last_seen_at?->toISOString(),
        ];
    }
}
