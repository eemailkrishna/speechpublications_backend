<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

// Public channel - no auth needed for testing
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return true;
});
