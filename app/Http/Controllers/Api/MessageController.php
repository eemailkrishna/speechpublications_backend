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
                $message->update([
                    'status' => 'read',
                    'read_at' => now(),
                ]);
                broadcast(new MessageRead($message))->toOthers();
            }

            return response()->json([
                'success' => true,
                'message' => 'Marked as read',
                'data' => [
                    'id' => $message->id,
                    'status' => 'read',
                    'read_at' => $message->read_at->toISOString(),
                ],
            ]);
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

    /**
     * POST /api/message/read-all
     * Mark all messages in a conversation as read for the current user.
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $validated = $request->validate([
                'conversation_id' => 'required|integer',
            ]);

            $userId = auth('api')->id();
            $conversationId = $validated['conversation_id'];

            $messages = Message::where('conversation_id', $conversationId)
                ->where('receiver_id', $userId)
                ->where('status', '!=', 'read')
                ->get();

            foreach ($messages as $msg) {
                $msg->update([
                    'status' => 'read',
                    'read_at' => now(),
                ]);

                try {
                    broadcast(new \App\Events\MessageRead($msg));
                } catch (\Exception $e) {
                    \Log::error('Broadcast read failed: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($messages) . ' messages marked as read',
                'data' => [
                    'conversation_id' => $conversationId,
                    'messages_updated' => count($messages),
                ],
            ]);
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
            \Log::error('MessageController@markAllAsRead error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => $e->getMessage() ?: 'An error occurred'
                ]
            ], 500);
        }
    }

    /**
     * GET /api/message/unread-count
     * Get unread message count for the current user.
     */
    public function unreadCount(Request $request)
    {
        try {
            $userId = auth('api')->id();

            $unread = Message::where('receiver_id', $userId)
                ->where('status', '!=', 'read')
                ->count();

            $unreadByConversation = Message::where('receiver_id', $userId)
                ->where('status', '!=', 'read')
                ->selectRaw('conversation_id, count(*) as unread_count')
                ->groupBy('conversation_id')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_unread' => $unread,
                    'by_conversation' => $unreadByConversation,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('MessageController@unreadCount error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => $e->getMessage() ?: 'An error occurred'
                ]
            ], 500);
        }
    }

    /**
     * GET /api/message/unread-by-sender
     * Get unread message count grouped by sender_id.
     */
    public function unreadBySender(Request $request)
    {
        try {
            $userId = auth('api')->id();

            $unread = Message::where('receiver_id', $userId)
                ->where('status', '!=', 'read')
                ->selectRaw('sender_id, count(*) as unread_count')
                ->groupBy('sender_id')
                ->get()
                ->pluck('unread_count', 'sender_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => $unread,
            ]);
        } catch (\Exception $e) {
            \Log::error('MessageController@unreadBySender error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => $e->getMessage() ?: 'An error occurred'
                ]
            ], 500);
        }
    }

    /**
     * Send a message between two users.
     * Body: { receiver_id: int, text?: string }
     * File: image (optional)
     */
    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'receiver_id' => 'required|integer',
                'text' => 'nullable|string|max:5000',
                'image' => 'nullable|image|max:5120',
            ]);

            $senderId = auth('api')->id();
            $receiverId = $validated['receiver_id'];

            if (empty($validated['text']) && !$request->hasFile('image')) {
                return response()->json(['message' => 'Text or image is required'], 422);
            }

            if ($senderId == $receiverId) {
                return response()->json(['message' => 'Cannot send message to yourself'], 422);
            }

            if (!\App\Models\User::find($receiverId)) {
                return response()->json(['message' => 'Receiver not found'], 404);
            }

            $conversation = Conversation::between($senderId, $receiverId);
            if (! $conversation) {
                $conversation = Conversation::create([
                    'user_one_id' => min($senderId, $receiverId),
                    'user_two_id' => max($senderId, $receiverId),
                ]);
            }

            $imageUrl = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('chat-images', 's3');
                $imageUrl = config('app.url') . '/storage/' . $path;
                try {
                    $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
                } catch (\Exception $e) {
                    $imageUrl = '/' . $path;
                }
            }

            $text = $validated['text'] ?? ($imageUrl ? '📷 Image' : '');
            $lastMessage = $imageUrl ? '📷 Image' : $text;

            $message = DB::transaction(function () use ($conversation, $senderId, $receiverId, $text, $imageUrl) {
                $message = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'text' => $text,
                    'image' => $imageUrl,
                    'status' => 'sent',
                ]);

                $conversation->update([
                    'last_message' => '📷 Image',
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
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => [
                    'id' => $message->id,
                    'conversation_id' => $message->conversation_id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'text' => $message->text,
                    'image' => $message->image,
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
            \Log::error('MessageController@sendMessage error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => $e->getMessage() ?: 'An error occurred'
                ]
            ], 500);
        }
    }

    /**
     * GET /api/message/get?sender_id=1&receiver_id=2
     * Get all messages between two users.
     */
    public function getMessages(Request $request)
    {
        try {
            $validated = $request->validate([
                'sender_id' => 'required|integer',
                'receiver_id' => 'required|integer',
            ]);

            $senderId = $validated['sender_id'];
            $receiverId = $validated['receiver_id'];

            $conversation = Conversation::between($senderId, $receiverId);
            if (! $conversation) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No conversation found between these users',
                ]);
            }

            $messages = Message::where('conversation_id', $conversation->id)
                ->with('sender:id,name,profile_photo')
                ->orderBy('created_at', 'desc')
                ->paginate($request->limit ?? 50);

            $items = array_reverse($messages->items());

            return response()->json([
                'success' => true,
                'data' => $items,
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                ],
            ]);
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
            \Log::error('MessageController@getMessages error: ' . $e->getMessage());
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
