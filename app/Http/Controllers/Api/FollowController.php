<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Follow;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Support\Str;

class FollowController extends Controller
{
    // API 22: Follow User
    public function follow($userId)
    {
        $currentUser = auth('api')->user();
        $targetUser = User::find($userId);

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ]
            ], 404);
        }

        if ($userId === $currentUser->id) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Cannot follow yourself',
                ]
            ], 400);
        }

        // Check if already following
        if ($currentUser->isFollowing($targetUser)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Already following this user',
                ]
            ], 400);
        }

        // Create follow relationship
        $currentUser->following()->attach($userId);

        // Update counts
        $currentUser->increment('following_count');
        $targetUser->increment('followers_count');

        // Send notification to followed user
        NotificationService::sendNotification(
            $userId,
            $currentUser->id,
            'follow'
        );

        return response()->json([
            'success' => true,
            'is_following' => true,
            'followers_count' => $targetUser->followers_count + 1,
        ]);
    }

    // API 23: Unfollow User
    public function unfollow($userId)
    {
        $currentUser = auth('api')->user();
        $targetUser = User::find($userId);

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ]
            ], 404);
        }

        if (!$currentUser->isFollowing($targetUser)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Not following this user',
                ]
            ], 400);
        }

        // Remove follow relationship
        $currentUser->following()->detach($userId);

        // Update counts
        $currentUser->decrement('following_count');
        $targetUser->decrement('followers_count');

        return response()->json([
            'success' => true,
            'is_following' => false,
            'followers_count' => $targetUser->followers_count - 1,
        ]);
    }

    // API 24: Get Followers List
    public function getFollowers($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ]
            ], 404);
        }

        $currentUserId = auth('api')->id();
        $followers = $user->followers()
            ->paginate(20);

        // Transform followers
        $followers->getCollection()->transform(function ($follower) use ($currentUserId) {
            return [
                'id' => $follower->id,
                'full_name' => $follower->full_name,
                'username' => $follower->username,
                'profile_photo' => $follower->profile_photo,
                'is_following' => $follower->id === $currentUserId ? false : 
                                 User::find($currentUserId)->isFollowing($follower),
            ];
        });

        return response()->json([
            'success' => true,
            'followers' => $followers->items(),
            'total_count' => $followers->total(),
        ]);
    }

    // API 25: Get Following List
    public function getFollowing($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ]
            ], 404);
        }

        $currentUserId = auth('api')->id();
        $following = $user->following()
            ->paginate(20);

        // Transform following
        $following->getCollection()->transform(function ($followee) use ($currentUserId) {
            return [
                'id' => $followee->id,
                'full_name' => $followee->full_name,
                'username' => $followee->username,
                'profile_photo' => $followee->profile_photo,
                'is_following' => $followee->id === $currentUserId ? false : 
                                 User::find($currentUserId)->isFollowing($followee),
            ];
        });

        return response()->json([
            'success' => true,
            'following' => $following->items(),
            'total_count' => $following->total(),
        ]);
    }
}
