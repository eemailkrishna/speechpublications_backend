<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Like;
use App\Models\Bookmark;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    // API 6: Get Home Feed
    public function getFeed(Request $request)
    {
        $validated = $request->validate([
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
            'feed_type' => 'nullable|in:for_you,following,trending',
        ]);

        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 20;
        $feedType = $validated['feed_type'] ?? 'for_you';
        $userId = auth('api')->id();

        // $query = Post::with(['user', 'likes', 'comments', 'bookmarks'])
        //     ->where('visibility', 'public');
        $query = Post::with(['user', 'likes', 'comments', 'bookmarks'])
    ->where('visibility', 'public')
    ->whereHas('user');

        if ($feedType === 'following') {
            // Posts from followed users
            $followingIds = auth('api')->user()->following()->pluck('following_id')->toArray();
            $query->whereIn('user_id', $followingIds);
        } elseif ($feedType === 'trending') {
            // Sort by likes and comments
            $query->orderBy('likes_count', 'desc')
                  ->orderBy('comments_count', 'desc');
        } else {
            // For you - personalized (can add algorithm here)
            $query->orderBy('created_at', 'desc');
        }
        $posts = $query->paginate($limit, ['*'], 'page', $page);
        $mediaUrls = [];
       
        $posts->getCollection()->transform(function ($post) use ($userId) {
            return [
                'id' => $post->id,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'username' => $post->user->username,
                    'profile_photo' => Storage::disk('s3')->url('profile/' . $post->user->profile_photo),
                ],
                'content' => $post->content,
                'media' => $post->media_urls
                ? collect(json_decode($post->media_urls))->map(fn ($file) => [
                    'type' => $post->media_type,
                    'url' => Storage::disk('s3')->url('media/' . $file),
                ])
                : [],
                'location' => $post->location,
                'likes_count' => $post->likes_count,
                'comments_count' => $post->comments_count,
                'shares_count' => $post->shares_count,
                'is_liked' => $post->likes()->where('user_id', $userId)->exists(),
                'is_bookmarked' => $post->bookmarks()->where('user_id', $userId)->exists(),
                'created_at' => $post->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'posts' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'total_pages' => $posts->lastPage(),
                'has_next' => $posts->hasMorePages(),
            ],
        ]);
    }

    // API 7: Create Post
    public function createPost(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'media_type' => 'nullable',
            'media' => 'nullable',
            'location' => 'nullable|string',
            'visibility' => 'nullable|in:public,private',
        ]);

        if ($request->hasFile('media') && $request->media_type) {
            foreach ($request->file('media') as $file) {
                $sizeInMB = $file->getSize() / 1024 / 1024;
                $mimeType = $file->getMimeType();
                if (str_starts_with($mimeType, 'image/')) {

                    if ($sizeInMB > 5) {
                        throw ValidationException::withMessages([
                            'media' => 'Each image must be less than 5 MB'
                        ]);
                    }

                }
                elseif (str_starts_with($mimeType, 'video/')) {

                    if ($sizeInMB > 20) {
                        throw ValidationException::withMessages([
                            'media' => 'Each video must be less than 20 MB'
                        ]);
                    }

                }
                else {
                    throw ValidationException::withMessages([
                        'media' => 'Only image and video files are allowed'
                    ]);
                }
            }
        }

        
       $mediaFiles = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $fileName = uniqid('', true) . '.' . $file->getClientOriginalExtension();
                Storage::disk('s3')->putFileAs(
                    'media',   // folder name in S3
                    $file,
                    $fileName
                );
                $mediaFiles[] = $fileName;
            }
        }

        $validated['media'] = json_encode($mediaFiles);
        $post = Post::create([
            'id' => Str::uuid(),
            'user_id' => auth('api')->id(),
            'content' => $validated['content'],
            'media_type' => $validated['media_type'] ?? null,
            'media_urls' => $validated['media'] ?: null,
            'location' => $validated['location'] ?? null,
            'visibility' => $validated['visibility'] ?? 'public',
        ]);

        // Increment user's post count
        auth('api')->user()->increment('posts_count');

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'post' => [
                'id' => $post->id,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'username' => $post->user->username,
                    'profile_photo' => Storage::disk('s3')->url('profile/' . $post->user->profile_photo),
                ],
                'content' => $post->content,
                'media' => $post->media_urls ? collect(json_decode($post->media_urls))->map(fn ($file) => Storage::disk('s3')->url('media/' . $file)) : [],
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'visibility' => $post->visibility,
                'created_at' => 'just now',
            ],
        ], 201);
    }

    // API 8: Toggle Like
    public function toggleLike($postId)
    {
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
        $liked = Like::where('user_id', $userId)->where('post_id', $postId)->first();

        if ($liked) {
            $liked->delete();
            $post->decrement('likes_count');
            $isLiked = false;
        } else {
            Like::create([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'post_id' => $postId,
                'created_at' => now(),
            ]);
            $post->increment('likes_count');
            $isLiked = true;
        }

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $post->likes_count,
        ]);
    }

    // API 9: Toggle Bookmark
    public function toggleBookmark($postId)
    {
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
        $bookmarked = Bookmark::where('user_id', $userId)->where('post_id', $postId)->first();

        if ($bookmarked) {
            $bookmarked->delete();
            $isBookmarked = false;
        } else {
            Bookmark::create([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'post_id' => $postId,
                'created_at' => now(),
            ]);
            $isBookmarked = true;
        }

        return response()->json([
            'success' => true,
            'is_bookmarked' => $isBookmarked,
        ]);
    }

    // API 10: Share Post
    public function sharePost($postId)
    {
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

        $post->increment('shares_count');

        return response()->json([
            'success' => true,
            'shares_count' => $post->shares_count,
            'share_url' => config('app.url') . "/post/$postId",
        ]);
    }

    // Get Single Post
    public function getPost($postId)
    {
        $post = Post::with('user')->find($postId);

        if (!$post || $post->visibility === 'private') {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'POST_NOT_FOUND',
                    'message' => 'Post does not exist',
                ]
            ], 404);
        }

        $userId = auth('api')->id();

        return response()->json([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'user' => [
                    'id' => $post->user->id,
                    'full_name' => $post->user->full_name,
                    'username' => $post->user->username,
                    'profile_photo' => $post->user->profile_photo,
                ],
                'content' => $post->content,
                'media' => $post->media_urls ? array_map(fn($url) => [
                    'type' => 'image',
                    'url' => $url,
                ], $post->media_urls) : [],
                'likes_count' => $post->likes_count,
                'comments_count' => $post->comments_count,
                'shares_count' => $post->shares_count,
                'is_liked' => $post->likes()->where('user_id', $userId)->exists(),
                'is_bookmarked' => $post->bookmarks()->where('user_id', $userId)->exists(),
                'created_at' => $post->created_at->toIso8601String(),
            ],
        ]);
    }
}