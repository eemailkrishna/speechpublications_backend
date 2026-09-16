<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\StoryController;

// Public routes (no authentication)
Route::group([], function () {
    // Authentication
    Route::post('auth/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('auth/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/complete-profile', [AuthController::class, 'completeProfile']);
    Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);

    // Test: Get token by user ID (for testing only)
    Route::get('test/token/{userId}', function ($userId) {
        $user = \App\Models\User::find($userId);
        if (!$user) return response()->json(['error' => 'User not found'], 404);
        $token = \Firebase\JWT\JWT::encode([
            'iat' => time(),
            'exp' => time() + 86400,
            'user_id' => $user->id,
            'phone_number' => $user->phone_number,
        ], config('app.jwt_secret'), 'HS256');
        return response()->json(['token' => $token, 'user' => ['id' => $user->id, 'name' => $user->name]]);
    });

    // Test chat routes (no auth for testing)
    Route::post('test/conversations', [ConversationController::class, 'store']);
    Route::post('test/messages', [MessageController::class, 'store']);
    Route::get('test/conversations/{id}/messages', [MessageController::class, 'index']);
});

// Protected routes (require authentication)
Route::middleware(\App\Http\Middleware\JwtMiddleware::class)->group(function () {
    // Authentication
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // User Profile
    Route::get('user/profile', [UserController::class, 'getProfile']);
    Route::post('user/profile', [UserController::class, 'updateProfile']);
    Route::get('user/{userId}', [UserController::class, 'getUserById']);
    Route::get('get-chat-users', [UserController::class, 'getChatUsers']);

    // Posts
    Route::get('feed/home', [PostController::class, 'getFeed']);
    Route::post('posts/create', [PostController::class, 'createPost']);
    Route::get('posts/{postId}', [PostController::class, 'getPost']);
    Route::post('posts/{postId}/like', [PostController::class, 'toggleLike']);
    Route::post('posts/{postId}/bookmark', [PostController::class, 'toggleBookmark']);
    Route::post('posts/{postId}/share', [PostController::class, 'sharePost']);

    // Comments
    Route::get('posts/{postId}/comments', [CommentController::class, 'getComments']);
    Route::post('posts/{postId}/comments', [CommentController::class, 'createComment']);
    Route::post('comments/{commentId}/reply', [CommentController::class, 'replyToComment']);
    Route::delete('comments/{commentId}', [CommentController::class, 'deleteComment']);
    Route::post('comments/{commentId}/like', [CommentController::class, 'toggleCommentLike']);

    // Follow System
    Route::post('users/{userId}/follow', [FollowController::class, 'follow']);
    Route::delete('users/{userId}/follow', [FollowController::class, 'unfollow']);
    Route::get('users/{userId}/followers', [FollowController::class, 'getFollowers']);
    Route::get('users/{userId}/following', [FollowController::class, 'getFollowing']);

    // Notifications
    Route::get('notifications', [NotificationController::class, 'getNotifications']);
    Route::put('notifications/{notificationId}/read', [NotificationController::class, 'markAsRead']);
    Route::put('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::put('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('notifications/{notificationId}', [NotificationController::class, 'deleteNotification']);
    Route::delete('notifications/clear-all', [NotificationController::class, 'clearAllNotifications']);
    Route::post('/send-notification', [NotificationController::class, 'sendNotification']);
    Route::post('save/fcm-token', [NotificationController::class, 'saveNotificationToken']);
    Route::get('/firebase-check', [NotificationController::class, 'check']);


    // Conversations
    Route::get('conversations', [ConversationController::class, 'index']);
    Route::post('conversations', [ConversationController::class, 'store']);

    // Messages
    Route::get('conversations/{id}/messages', [MessageController::class, 'index']);
    Route::post('messages', [MessageController::class, 'store']);
    Route::post('messages/{id}/read', [MessageController::class, 'markAsRead']);

    // Broadcasting auth (for WebSocket channel subscriptions)
    Route::post('broadcasting/auth', function (Request $request) {
        $user = auth('api')->user();
        $request->setUserResolver(fn () => $user);
        return \Illuminate\Support\Facades\Broadcast::auth($request);
    });



    // Search
    Route::get('search', [SearchController::class, 'search']);

    // Stories
    Route::get('stories', [StoryController::class, 'index']);
    Route::post('stories', [StoryController::class, 'store']);
    Route::post('stories/{id}/view', [StoryController::class, 'view']);
    Route::delete('stories/{id}', [StoryController::class, 'destroy']);
    Route::get('stories/{id}/viewers', [StoryController::class, 'viewers']);
    Route::post('stories/{storyId}/view', [StoryController::class, 'itemView']);
    Route::get('stories/{storyId}/viewers', [StoryController::class, 'itemViewers']);
    Route::post('stories/{storyId}/like', [StoryController::class, 'itemLike']);
    Route::delete('stories/{storyId}', [StoryController::class, 'itemDelete']);
    
});

