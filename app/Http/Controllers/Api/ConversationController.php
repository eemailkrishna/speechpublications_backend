<?php

namespace App\Http\Controllers\Api;

use App\Models\Conversation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userId = $request->input('sender_id', 1);

            $conversations = Conversation::where('user_one_id', $userId)
                ->orWhere('user_two_id', $userId)
                ->with(['userOne:id,name,profile_photo', 'userTwo:id,name,profile_photo'])
                ->orderBy('last_message_at', 'desc')
                ->paginate(20);

            $conversations->getCollection()->transform(function ($conv) use ($userId) {
                $other = $conv->getOtherUser($userId);
                $unreadCount = $conv->messages()
                    ->where('receiver_id', $userId)
                    ->where('status', '!=', 'read')
                    ->count();

                return [
                    'id' => $conv->id,
                    'user' => [
                        'id' => $other->id,
                        'name' => $other->name,
                        'avatar' => $other->profile_photo,
                    ],
                    'last_message' => $conv->last_message,
                    'last_message_at' => $conv->last_message_at?->toISOString(),
                    'unread_count' => $unreadCount,
                ];
            });

            return response()->json($conversations);
        } catch (\Exception $e) {
            \Log::error('ConversationController@index error: ' . $e->getMessage());
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
                'user_id' => 'required|integer',
                'sender_id' => 'nullable|integer',
            ]);

            // Use sender_id if provided (test mode), otherwise use auth user
            $userId = $validated['sender_id'] ?? auth('api')->id();
            $otherId = $validated['user_id'];

            if (!$userId) {
                return response()->json(['message' => 'sender_id required'], 422);
            }

            if (!\App\Models\User::find($userId) || !\App\Models\User::find($otherId)) {
                return response()->json(['message' => 'User not found. Use existing IDs: 42,43,44,45,46,47'], 404);
            }

            if ($userId == $otherId) {
                return response()->json(['message' => 'Cannot create conversation with yourself'], 422);
            }

            $conversation = Conversation::between($userId, $otherId);

            if (! $conversation) {
                $conversation = Conversation::create([
                    'user_one_id' => min($userId, $otherId),
                    'user_two_id' => max($userId, $otherId),
                ]);
            }

            $conversation->load(['userOne:id,name,profile_photo', 'userTwo:id,name,profile_photo']);

            $other = $conversation->getOtherUser($userId);

            return response()->json([
                'id' => $conversation->id,
                'user' => [
                    'id' => $other->id,
                    'name' => $other->name,
                    'avatar' => $other->profile_photo,
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
            \Log::error('ConversationController@store error: ' . $e->getMessage());
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
