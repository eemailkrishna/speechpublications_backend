<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Like;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    // API 11: Get Post Comments
    public function getComments($postId, Request $request)
    {
        $validated = $request->validate([
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 20;

        $post = Post::find($postId);
       
        if (!$post) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'POST_NOT_FOUND',
                    'message' => 'Post does not exist',
                ]
            ], 404);
        }

        $userId = auth('api')->id();
        $comments = $post->comments()
            ->whereNull('parent_comment_id')
            ->with(['user', 'replies.user'])
            ->paginate($limit, ['*'], 'page', $page);

        $comments->getCollection()->transform(function ($comment) use ($userId) {
            return [
                'id' => $comment->id,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'username' => $comment->user->username,
                    'profile_photo' => $comment->user->profile_photo,
                ],
                'content' => $comment->content,
                'likes_count' => $comment->likes_count,
                'replies_count' => $comment->replies_count,
                'is_liked' => $comment->likes()->where('user_id', $userId)->exists(),
                'created_at' => $comment->created_at ? $comment->created_at->diffForHumans() : null,
                'replies' => $comment->replies->map(function ($reply) use ($userId) {
                    return [
                        'id' => $reply->id,
                        'user' => [
                            'id' => $reply->user->id,
                            'name' => $reply->user->name,
                            'username' => $reply->user->username,
                            'profile_photo' => $reply->user->profile_photo,
                        ],
                        'content' => $reply->content,
                        'likes_count' => $reply->likes_count,
                        'is_liked' => $reply->likes()->where('user_id', $userId)->exists(),
                        'created_at' => $reply->created_at ? $reply->created_at->diffForHumans() : null,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'success' => true,
            'comments' => $comments->items(),
            'pagination' => [
                'current_page' => $comments->currentPage(),
                'total_comments' => $comments->total(),
                'has_next' => $comments->hasMorePages(),
            ],
        ]);
    }

    // API 12: Create Comment
    public function createComment($postId, Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_comment_id' => 'nullable|string|exists:comments,id',
        ]);

        $post = Post::find($postId);
        if (!$post) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'POST_NOT_FOUND',
                    'message' => 'Post does not exist',
                ]
            ], 404);
        }

        $comment = Comment::create([
            'id' => Str::uuid(),
            'post_id' => $postId,
            'user_id' => auth('api')->id(),
            'parent_comment_id' => $validated['parent_comment_id'] ?? null,
            'content' => $validated['content'],
            'created_at' => now(),
        ]);

        // Increment comment count
        $post->increment('comments_count');

        // Increment replies count if reply
        if ($validated['parent_comment_id'] ?? false) {
            Comment::find($validated['parent_comment_id'])->increment('replies_count');
        }

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user' => [
                    'id' => $comment->user->id,
                    'full_name' => $comment->user->full_name,
                    'username' => $comment->user->username,
                    'profile_photo' => $comment->user->profile_photo,
                ],
                'content' => $comment->content,
                'likes_count' => 0,
                'replies_count' => 0,
                'created_at' => 'just now',
            ],
        ], 201);
    }

    // API 13: Reply to Comment
    public function replyToComment($commentId, Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $parentComment = Comment::find($commentId);
        if (!$parentComment) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'COMMENT_NOT_FOUND',
                    'message' => 'Comment does not exist',
                ]
            ], 404);
        }

        $reply = Comment::create([
            'id' => Str::uuid(),
            'post_id' => $parentComment->post_id,
            'user_id' => auth('api')->id(),
            'parent_comment_id' => $commentId,
            'content' => $validated['content'],
            'created_at' => now(),
        ]);

        // Increment parent comment's replies count
        $parentComment->increment('replies_count');

        return response()->json([
            'success' => true,
            'reply' => [
                'id' => $reply->id,
                'user' => [
                    'id' => $reply->user->id,
                    'name' => $reply->user->name,
                    'username' => $reply->user->username,
                    'profile_photo' => $reply->user->profile_photo,
                ],
                'content' => $reply->content,
                'likes_count' => 0,
                'created_at' => 'just now',
            ],
        ], 201);
    }

    // API 13: Delete Comment
    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'COMMENT_NOT_FOUND',
                    'message' => 'Comment does not exist',
                ]
            ], 404);
        }

        // Check authorization
        if ($comment->user_id !== auth('api')->id()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'message' => 'Not authorized to delete this comment',
                ]
            ], 403);
        }

        $post = $comment->post;
        $post->decrement('comments_count');

        if ($comment->parent_comment_id) {
            Comment::find($comment->parent_comment_id)->decrement('replies_count');
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }

    // Toggle like on comment
    public function toggleCommentLike($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'COMMENT_NOT_FOUND',
                    'message' => 'Comment does not exist',
                ]
            ], 404);
        }

        $userId = auth('api')->id();
        $liked = Like::where('user_id', $userId)->where('comment_id', $commentId)->first();

        if ($liked) {
            $liked->delete();
            $comment->decrement('likes_count');
            $isLiked = false;
        } else {
            Like::create([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'comment_id' => $commentId,
                'created_at' => now(),
            ]);
            $comment->increment('likes_count');
            $isLiked = true;
        }

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $comment->likes_count,
        ]);
    }
}
