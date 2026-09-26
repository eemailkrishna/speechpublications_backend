<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OnlineStatusController extends Controller
{
    private function getPusher()
    {
        return new \Pusher\Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true,
                'timeout' => 10,
            ]
        );
    }

    private function getOnlineUserIds(): array
    {
        try {
            $pusher = $this->getPusher();
            $result = $pusher->getPresenceUsers('presence-online');
            $ids = [];
            if (isset($result->users)) {
                foreach ($result->users as $u) {
                    $ids[] = $u->id;
                }
            }

            if (!empty($ids)) {
                Cache::put('online_user_ids', $ids, 30);
            }

            return $ids;
        } catch (\Exception $e) {
            \Log::warning('Pusher getPresenceUsers failed: ' . $e->getMessage());
            return Cache::get('online_user_ids', []);
        }
    }

    /**
     * GET /api/user/{userId}/status
     */
    public function getStatus($userId)
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            $onlineIds = $this->getOnlineUserIds();
            $isOnline = in_array((string) $userId, $onlineIds);

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'is_online' => $isOnline,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('OnlineStatusController@getStatus error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => ['code' => 'SERVER_ERROR', 'message' => $e->getMessage() ?: 'An error occurred']
            ], 500);
        }
    }

    /**
     * GET /api/users/online-status?ids=1,2,3
     */
    public function getMultipleStatus(Request $request)
    {
        try {
            $ids = $request->input('ids');
            if (!$ids || !is_array($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provide ids as comma-separated list (e.g., ?ids=1,2,3)'
                ], 422);
            }

            $users = User::whereIn('id', $ids)->select('id', 'name')->get();
            $onlineIds = $this->getOnlineUserIds();

            $data = $users->map(function ($user) use ($onlineIds) {
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'is_online' => in_array((string) $user->id, $onlineIds),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('OnlineStatusController@getMultipleStatus error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => ['code' => 'SERVER_ERROR', 'message' => $e->getMessage() ?: 'An error occurred']
            ], 500);
        }
    }

    /**
     * GET /api/users/online
     */
    public function getOnlineUsers()
    {
        try {
            $onlineIds = $this->getOnlineUserIds();
            $onlineUsers = [];

            if (!empty($onlineIds)) {
                $onlineUsers = User::whereIn('id', $onlineIds)
                    ->select('id', 'name', 'profile_photo')
                    ->get()
                    ->map(fn($u) => [
                        'user_id' => $u->id,
                        'name' => $u->name,
                        'profile_photo' => $u->profile_photo,
                    ])
                    ->toArray();
            }

            return response()->json([
                'success' => true,
                'data' => $onlineUsers,
                'total_online' => count($onlineUsers),
            ]);
        } catch (\Exception $e) {
            \Log::error('OnlineStatusController@getOnlineUsers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => ['code' => 'SERVER_ERROR', 'message' => $e->getMessage() ?: 'An error occurred']
            ], 500);
        }
    }
}
