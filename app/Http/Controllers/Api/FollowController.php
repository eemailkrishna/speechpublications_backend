<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Follow;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow($userId)
    {
        $currentUser = auth('api')->user();
        $targetUser = User::find($userId);

        if (! $targetUser) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ],
            ], 404);
        }

        if ($userId == $currentUser->id) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Cannot follow yourself',
                ],
            ], 400);
        }

        if ($currentUser->isFollowing($targetUser)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Already following this user',
                ],
            ], 400);
        }

        $currentUser->following()->attach($userId);
        $currentUser->increment('following_count');
        $targetUser->increment('followers_count');

        return response()->json([
            'success' => true,
            'is_following' => true,
            'followers_count' => $targetUser->followers_count,
        ]);
    }

    public function unfollow($userId)
    {
        $currentUser = auth('api')->user();
        $targetUser = User::find($userId);

        if (! $targetUser) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ],
            ], 404);
        }

        if (! $currentUser->isFollowing($targetUser)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Not following this user',
                ],
            ], 400);
        }

        $currentUser->following()->detach($userId);
        $currentUser->decrement('following_count');
        $targetUser->decrement('followers_count');

        return response()->json([
            'success' => true,
            'is_following' => false,
            'followers_count' => $targetUser->followers_count,
        ]);
    }

    public function getFollowers(Request $request, $userId)
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ],
            ], 404);
        }

        $currentUserId = auth('api')->id();
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        $followers = $user->followers()->paginate($limit, ['*'], 'page', $page);

        $currentUserFollowingIds = $currentUserId
            ? User::find($currentUserId)->following()->pluck('users.id')->toArray()
            : [];

        $followers->getCollection()->transform(function ($follower) use ($currentUserId, $currentUserFollowingIds) {
            $isCurrentUser = $follower->id == $currentUserId;
            $isFollowing = in_array($follower->id, $currentUserFollowingIds);

            return [
                'id' => $follower->id,
                'full_name' => $follower->full_name,
                'username' => $follower->username,
                'profile_photo' => $follower->profile_photo,
                'is_current_user' => $isCurrentUser,
                'is_following' => $isCurrentUser ? false : $isFollowing,
                'follows_you' => !$isCurrentUser && $isFollowing,
            ];
        });

        return response()->json([
            'success' => true,
            'followers' => $followers->items(),
            'total_count' => $followers->total(),
            'current_page' => $followers->currentPage(),
            'last_page' => $followers->lastPage(),
        ]);
    }

    public function getFollowing(Request $request, $userId)
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ],
            ], 404);
        }

        $currentUserId = auth('api')->id();
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        $following = $user->following()->paginate($limit, ['*'], 'page', $page);

        $currentUserFollowingIds = $currentUserId
            ? User::find($currentUserId)->following()->pluck('users.id')->toArray()
            : [];

        $following->getCollection()->transform(function ($followee) use ($currentUserId, $currentUserFollowingIds) {
            $isCurrentUser = $followee->id == $currentUserId;
            $isFollowing = in_array($followee->id, $currentUserFollowingIds);

            return [
                'id' => $followee->id,
                'full_name' => $followee->full_name,
                'username' => $followee->username,
                'profile_photo' => $followee->profile_photo,
                'is_current_user' => $isCurrentUser,
                'is_following' => $isCurrentUser ? false : $isFollowing,
                'follows_you' => !$isCurrentUser && $isFollowing,
            ];
        });

        return response()->json([
            'success' => true,
            'following' => $following->items(),
            'total_count' => $following->total(),
            'current_page' => $following->currentPage(),
            'last_page' => $following->lastPage(),
        ]);
    }
}
