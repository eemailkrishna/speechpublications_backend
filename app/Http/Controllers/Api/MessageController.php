<?php

namespace App\Http\Controllers\Api;

use App\Models\Conversation;
use App\Models\Message;
use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Events\MessageRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(Request $request, $conversationId)
    {
        try {
            $userId = $request->input('sender_id', 1);

            $conversation = Conversation::find($conversationId);
            if (! $conversation) {
                return response()->json(['message' => 'Conversation not found'], 404);
            }

            $messages = Message::where('conversation_id', $conversationId)
                ->with('sender:id,name,profile_photo')
                ->orderBy('created_at', 'desc')
                ->paginate($request->limit ?? 50);

            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('MessageController@index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => $e->getMessage() ?: 'An error occurred'
                ]
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'sender_id' => 'nullable|integer',
                'receiver_id' => 'required|integer',
                'text' => 'required|string|max:5000',
            ]);

            $senderId = $validated['sender_id'] ?? auth('api')->id();
            $receiverId = $validated['receiver_id'];

            if (!$senderId) {
                return response()->json(['message' => 'sender_id required'], 422);
            }

            if (!\App\Models\User::find($senderId) || !\App\Models\User::find($receiverId)) {
                return response()->json(['message' => 'User not found. Use existing IDs: 42,43,44,45,46,47'], 404);
            }

            if ($senderId == $receiverId) {
                return response()->json(['message' => 'Cannot send message to yourself'], 422);
            }

            $conversation = Conversation::between($senderId, $receiverId);
            if (! $conversation) {
                $conversation = Conversation::create([
                    'user_one_id' => min($senderId, $receiverId),
                    'user_two_id' => max($senderId, $receiverId),
                ]);
            }

            $message = DB::transaction(function () use ($conversation, $senderId, $receiverId, $validated) {
                $message = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'text' => $validated['text'],
                    'status' => 'sent',
                ]);

                $conversation->update([
                    'last_message' => $validated['text'],
                    'last_message_at' => now(),
                ]);

                return $message;
            });

            $message->load('sender:id,name,profile_photo');

            try {
                broadcast(new MessageSent($message));
            } catch (\Exception $e) {
                \Log::error('Broadcast failed: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'sent',
                'data' => [
                    'id' => $message->id,
                    'conversation_id' => $message->conversation_id,
                    'sender_id' => $message->sender_id,
                    'text' => $message->text,
                    'status' => $message->status,
                    'created_at' => $message->created_at->toISOString(),
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'avatar' => $message->sender->profile_photo,
                    ],
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Validation failed',
                    'details' => $e->errors()
                ]
            ], 422);
        } catch (\Exception $e) {
            \Log::error('MessageController@store error: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => $e->getMessage() ?: 'An error occurred'
                ]
            ], 500);
        }
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $message = Message::find($id);
            if (! $message) {
                return response()->json(['message' => 'Message not found'], 404);
            }

            if ($message->status !== 'read') {
                $message->update(['status' => 'read']);
                broadcast(new MessageRead($message))->toOthers();
            }

            return response()->json(['message' => 'marked as read']);
        } catch (\Exception $e) {
            \Log::error('MessageController@markAsRead error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => $e->getMessage() ?: 'An error occurred'
                ]
            ], 500);
        }
    }
}
