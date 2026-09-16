<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'last_message',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Find or create conversation between two users (order-independent)
     */
    public static function between($userA, $userB)
    {
        return static::where(function ($q) use ($userA, $userB) {
            $q->where('user_one_id', $userA)->where('user_two_id', $userB);
        })->orWhere(function ($q) use ($userA, $userB) {
            $q->where('user_one_id', $userB)->where('user_two_id', $userA);
        })->first();
    }

    /**
     * Get the other user in this conversation
     */
    public function getOtherUser($userId)
    {
        return $this->user_one_id == $userId ? $this->userTwo : $this->userOne;
    }
}
