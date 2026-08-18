<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Exception;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    // API 14: Get Notifications

     public function sendNotification(Request $request)
    {
        // ✅ JWT middleware se authenticated user
        $authUser = auth('api')->user(); // from JwtMiddleware

        // ✅ Validate request
        $request->validate([
            'title'   => 'required|string',
            'body'    => 'required|string',
        ]);

        // ✅ Jis user ko notification bhejni hai
        $user = User::find($authUser->id);

        if (!$user->fcm_token) {
            return response()->json([
                'status' => false,
                'message' => 'FCM token not found for this user'
            ], 400);
        }

        // ✅ Firebase notification message
        $message = CloudMessage::withTarget('token', $user->fcm_token)
            ->withNotification(FirebaseNotification::create(
                $request->title,
                $request->body
            ))
            ->withData([
                'sent_by' => (string) $authUser->id,
                'type'    => 'general'
            ]);

        // ✅ Send notification
        Firebase::messaging()->send($message);

        return response()->json([
            'status' => true,
            'message' => 'Notification sent successfully'
        ]);
    }
     public function saveNotificationToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string'
        ]);
        $user = auth('api')->user();
        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
       $isSave = User::where('id', $user->id)->update(['fcm_token' => $request->fcm_token]);
        

       if(!$isSave) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to save FCM token'
            ], 500);
        }   
        return response()->json([
            'status' => true,
            'message' => 'FCM token saved'
        ]);
    }

    public function getNotifications(Request $request)
    {
        $validated = $request->validate([
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
            'filter' => 'nullable|in:all,likes,comments,followers',
        ]);

        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 20;
        $filter = $validated['filter'] ?? 'all';
        $userId = auth('api')->id();

        $query = Notification::where('user_id', $userId);

        // Apply filter
        if ($filter === 'likes') {
            $query->where('type', 'like');
        } elseif ($filter === 'comments') {
            $query->whereIn('type', ['comment', 'reply']);
        } elseif ($filter === 'followers') {
            $query->where('type', 'follow');
        }

        // Get unread count
        $unreadCount = (clone $query)->where('is_read', false)->count();

        // Paginate
        $notifications = $query->with('actor')
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        // Transform notifications into requested shape
        $transformed = $notifications->getCollection()->transform(function ($notif) {
            $actor = $notif->actor;

            // message composition -- try to include extra text if available
            $actionText = '';
            if ($notif->type === 'like') {
                $actionText = 'liked your post';
            } elseif ($notif->type === 'comment') {
                $commentText = data_get($notif, 'data.comment') ?? data_get($notif, 'payload.comment') ?? null;
                $actionText = $commentText ? 'commented: "' . $commentText . '"' : 'commented on your post';
            } elseif ($notif->type === 'reply') {
                $replyText = data_get($notif, 'data.reply') ?? data_get($notif, 'payload.reply') ?? null;
                $actionText = $replyText ? 'replied: "' . $replyText . '"' : 'replied to your comment';
            } elseif ($notif->type === 'follow') {
                $actionText = 'started following you';
            } elseif ($notif->type === 'mention') {
                $actionText = 'mentioned you in a post';
            } else {
                $actionText = $notif->type;
            }

            $thumbnail = null;
            if (!empty($notif->post_id)) {
                // try to use stored thumbnail url if available on notification
                $thumbnail = data_get($notif, 'data.thumbnail') ?? data_get($notif, 'payload.thumbnail') ?? null;
            }

            return [
                'id' => (string) $notif->id,
                'type' => $notif->type,
                'user' => [
                    'id' => $actor->id ?? null,
                    'name' => $actor->full_name ?? ($actor->name ?? null),
                    'username' => $actor->username ?? null,
                    'profile_photo' => isset($actor->profile_photo) && $actor->profile_photo ? Storage::disk('s3')->url('profile/' . $actor->profile_photo) : null,
                ],
                'message' => $actionText,
                   'timeAgo' => (isset($notif->created_at) && !empty($notif->created_at)) ? \Carbon\Carbon::parse($notif->created_at)->diffForHumans() : null,
                'isRead' => (bool) $notif->is_read,
                'thumbnail' => $thumbnail,
            ];
        })->values()->all();

        return response()->json([
            'success' => true,
            'data' => $transformed,
            'unread_count' => $unreadCount,
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'total_pages' => $notifications->lastPage(),
            ],
        ]);
    }

    // API 15: Mark Notification as Read
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'NOTIFICATION_NOT_FOUND',
                    'message' => 'Notification does not exist',
                ]
            ], 404);
        }

        // Check authorization
        if ($notification->user_id !== auth('api')->id()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'message' => 'Not authorized',
                ]
            ], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    // API 16: Mark All Notifications as Read
    public function markAllAsRead()
    {
        Notification::where('user_id', auth('api')->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'NOTIFICATION_NOT_FOUND',
                    'message' => 'Notification does not exist',
                ]
            ], 404);
        }

        if ($notification->user_id !== auth('api')->id()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'message' => 'Not authorized',
                ]
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }

    public function clearAllNotifications()
    {
        $deletedCount = Notification::where('user_id', auth('api')->id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared',
            'deleted_count' => $deletedCount,
        ]);
    }

     public function check()
    {
        try {
            // Firebase messaging service init
            Firebase::messaging();

            return response()->json([
                'status' => true,
                'message' => 'Firebase connected successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Firebase connection failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
