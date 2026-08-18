<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NotificationService
{
    /**
     * Send notification to a user
     * 
     * @param int $userId - User ID to send notification to
     * @param int $actorId - User ID of the person performing the action
     * @param string $type - Type of notification (follow, like, comment, etc)
     * @param string|null $title - Title of the notification
     * @param string|null $body - Body of the notification
     * @param int|null $postId - Post ID if related to a post
     * @param int|null $commentId - Comment ID if related to a comment
     * @return bool - Returns true if notification sent successfully
     */
    public static function sendNotification(
        $userId,
        $actorId,
        $type,
        $title = null,
        $body = null,
        $postId = null,
        $commentId = null
    ) {
        try {
            $user = User::find($userId);
            
            if (!$user) {
                return false;
            }

            // Create database notification
            Notification::create([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'actor_id' => $actorId,
                'type' => $type,
                'post_id' => $postId,
                'comment_id' => $commentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send Firebase notification if FCM token exists
            if ($user->fcm_token) {
                $actor = User::find($actorId);
                
                // Default titles and bodies based on type
                if (!$title || !$body) {
                    switch ($type) {
                        case 'follow':
                            $title = $title ?? 'New Follower';
                            $body = $body ?? $actor->full_name . ' started following you';
                            break;
                        case 'like':
                            $title = $title ?? 'Post Liked';
                            $body = $body ?? $actor->full_name . ' liked your post';
                            break;
                        case 'comment':
                            $title = $title ?? 'New Comment';
                            $body = $body ?? $actor->full_name . ' commented on your post';
                            break;
                        case 'reply':
                            $title = $title ?? 'New Reply';
                            $body = $body ?? $actor->full_name . ' replied to your comment';
                            break;
                        default:
                            $title = $title ?? 'Notification';
                            $body = $body ?? 'You have a new notification';
                    }
                }

                $message = CloudMessage::withTarget('token', $user->fcm_token)
                    ->withNotification(FirebaseNotification::create($title, $body))
                    ->withData([
                        'sent_by' => (string) $actorId,
                        'type' => $type,
                        'notification_id' => Str::uuid(),
                    ]);

                Firebase::messaging()->send($message);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Notification sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk notifications
     * 
     * @param array $userIds - Array of user IDs to send notifications to
     * @param int $actorId - User ID of the person performing the action
     * @param string $type - Type of notification
     * @param string|null $title - Title of the notification
     * @param string|null $body - Body of the notification
     * @return int - Number of notifications sent
     */
    public static function sendBulkNotifications(
        $userIds,
        $actorId,
        $type,
        $title = null,
        $body = null
    ) {
        $count = 0;
        foreach ($userIds as $userId) {
            if (self::sendNotification($userId, $actorId, $type, $title, $body)) {
                $count++;
            }
        }
        return $count;
    }
}
