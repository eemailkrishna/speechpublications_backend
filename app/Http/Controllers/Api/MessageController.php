<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    // API 17: Get Conversations
    public function getConversations(Request $request)
    {
        $validated = $request->validate([
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 20;
        $userId = auth('api')->id();

        // Get unique conversation IDs
        $conversationIds = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })->distinct('conversation_id')->pluck('conversation_id');

        // Get last message for each conversation
        $messages = Message::whereIn('conversation_id', $conversationIds)
            ->orderBy('created_at', 'desc')
            ->distinct('conversation_id')
            ->paginate($limit, ['*'], 'page', $page);

        $conversations = [];

        foreach ($messages as $message) {
            $otherUserId = $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            $otherUser = User::find($otherUserId);

            // Get unread count
            $unreadCount = Message::where('conversation_id', $message->conversation_id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            $conversations[] = [
                'id' => $message->conversation_id,
                'user' => [
                    'id' => $otherUser->id,
                    'full_name' => $otherUser->full_name,
                    'username' => $otherUser->username,
                    'profile_photo' => $otherUser->profile_photo,
                    'is_online' => false, // TODO: Implement online status via Redis
                ],
                'last_message' => [
                    'content' => $message->content,
                    'sender_id' => $message->sender_id,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at ? $message->created_at->diffForHumans() : null,
                ],
                'unread_count' => $unreadCount,
                'updated_at' => $message->created_at ? $message->created_at->diffForHumans() : null,
            ];
        }

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }

    // API 18: Get Messages in Conversation
    public function getMessages($conversationId, Request $request)
    {
        $validated = $request->validate([
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 50;
        $userId = auth('api')->id();

        $messages = Message::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->paginate($limit, ['*'], 'page', $page);

        // Get other user in conversation
        $firstMessage = $messages->first();
        $otherUserId = $firstMessage->sender_id === $userId ? $firstMessage->receiver_id : $firstMessage->sender_id;
        $otherUser = User::find($otherUserId);

        if(!$otherUser) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'The other user in this conversation does not exist.',
                ]
            ], 404);
        }
        // Mark received messages as read
        Message::where('conversation_id', $conversationId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => @$otherUser->id,
                'full_name' => @$otherUser->full_name,
                'username' => @$otherUser->username,
                'profile_photo' => @$otherUser->profile_photo,
                'is_online' => false,
            ],
            'messages' => $messages->map(function ($msg) use ($userId) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'content' => $msg->content,
                    'type' => $msg->type,
                    'is_read' => $msg->is_read,
                    'created_at' => $msg->created_at->format('h:i A'),
                ];
            })->toArray(),
        ]);
    }

    // API 19: Send Message
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|string|exists:users,id',
            'content' => 'required|string|max:5000',
            'type' => 'nullable|in:text,image,video,audio',
        ]);

        $senderId = auth('api')->id();
        $receiverId = $validated['receiver_id'];

        if ($senderId === $receiverId) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Cannot send message to yourself',
                ]
            ], 400);
        }

        // Generate or get conversation ID
        $conversationId = $this->getOrCreateConversationId($senderId, $receiverId);

        $message = Message::create([
            'id' => Str::uuid(),
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'conversation_id' => $conversationId,
            'content' => $validated['content'],
            'type' => $validated['type'] ?? 'text',
            'is_read' => false,
            'created_at' => now(),
        ]);

        // TODO: Emit WebSocket event for real-time delivery

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'content' => $message->content,
                'type' => $message->type,
                'is_read' => false,
                'created_at' => $message->created_at->format('h:i A'),
            ],
        ], 201);
    }

    // API 20: Mark Messages as Read
    public function markMessagesAsRead($conversationId)
    {
        Message::where('conversation_id', $conversationId)
            ->where('receiver_id', auth('api')->id())
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
        ]);
    }

    // Helper: Get or create conversation ID
    private function getOrCreateConversationId($senderId, $receiverId)
    {
        // Look for existing conversation
        $existingMessage = Message::where(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $senderId)->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $receiverId)->where('receiver_id', $senderId);
        })->first();

        if ($existingMessage) {
            return $existingMessage->conversation_id;
        }

        // Create new conversation ID
        return Str::uuid();
    }
}
