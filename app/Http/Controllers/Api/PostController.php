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
                'media' => $this->mediaPayload($post),
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
        // media_type is no longer accepted from the client -
        // the backend detects image/video from the uploaded file itself.
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'media' => 'nullable',
            'media.*' => 'file',
            'thumbnails' => 'nullable',
            'thumbnails.*' => 'file',
            'location' => 'nullable|string',
            'visibility' => 'nullable|in:public,private',
        ]);

        $files = $request->file('media') ?: [];
        if (! is_array($files)) {
            $files = [$files];
        }

        $posters = $request->file('thumbnails') ?: [];
        if (! is_array($posters)) {
            $posters = [$posters];
        }

        $mediaFiles = [];
        $mediaTypes = [];
        $mediaThumbnails = [];

        foreach ($files as $index => $file) {
            $mime = (string) $file->getMimeType();
            $isImage = str_starts_with($mime, 'image/');
            $isVideo = str_starts_with($mime, 'video/');
            $sizeInMB = $file->getSize() / 1024 / 1024;

            if (! $isImage && ! $isVideo) {
                throw ValidationException::withMessages([
                    'media' => 'Only image and video files are allowed',
                ]);
            }

            if ($isImage && $sizeInMB > 5) {
                throw ValidationException::withMessages([
                    'media' => 'Each image must be less than 5 MB',
                ]);
            }

            if ($isVideo && $sizeInMB > 20) {
                throw ValidationException::withMessages([
                    'media' => 'Each video must be less than 20 MB',
                ]);
            }

            $fileName = uniqid('', true) . '.' . $this->mediaExtension($file, $mime);
            Storage::disk('s3')->putFileAs('media', $file, $fileName);

            $mediaFiles[] = $fileName;
            $mediaTypes[] = $isVideo ? 'video' : 'image';
            $mediaThumbnails[] = $isVideo
                ? $this->resolveVideoThumbnail($posters[$index] ?? null, $file, $fileName)
                : null;
        }

        $post = Post::create([
            'id' => Str::uuid(),
            'user_id' => auth('api')->id(),
            'content' => $validated['content'],
            'media_type' => $this->postMediaType($mediaTypes),
            'media_urls' => json_encode($mediaFiles),
            'media_types' => $mediaTypes ?: null,
            'media_thumbnails' => $mediaThumbnails ?: null,
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
                'media' => $post->media_urls
                    ? array_map(fn ($file) => Storage::disk('s3')->url('media/' . $file), $this->mediaFiles($post))
                    : [],
                'media_type' => $post->media_type,
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
                'media' => $this->mediaPayload($post),
                'likes_count' => $post->likes_count,
                'comments_count' => $post->comments_count,
                'shares_count' => $post->shares_count,
                'is_liked' => $post->likes()->where('user_id', $userId)->exists(),
                'is_bookmarked' => $post->bookmarks()->where('user_id', $userId)->exists(),
                'created_at' => $post->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Raw media filenames stored on the post (handles legacy rows).
     */
    protected function mediaFiles(Post $post): array
    {
        $media = $post->media_urls;

        if (is_string($media)) {
            $media = json_decode($media, true);
        }

        return is_array($media) ? array_values($media) : [];
    }

    /**
     * Media payload for feed / single post responses.
     * type is detected on upload (image|video), thumbnail only for video.
     */
    protected function mediaPayload(Post $post): array
    {
        $files = $this->mediaFiles($post);

        if (! $files) {
            return [];
        }

        $types = is_array($post->media_types) ? array_values($post->media_types) : [];
        $thumbnails = is_array($post->media_thumbnails) ? array_values($post->media_thumbnails) : [];

        return array_map(function ($file, $index) use ($types, $thumbnails) {
            $type = $types[$index] ?? $this->typeFromFilename($file);

            $thumbnail = null;
            if ($type === 'video' && ! empty($thumbnails[$index])) {
                $thumbnail = Storage::disk('s3')->url('media/thumbnails/' . $thumbnails[$index]);
            }

            return [
                'type' => $type,
                'url' => Storage::disk('s3')->url('media/' . $file),
                'thumbnail' => $thumbnail,
            ];
        }, $files, array_keys($files));
    }

    /**
     * Fallback type detection for posts created before media_types existed.
     */
    protected function typeFromFilename(string $file): string
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        return in_array($extension, ['mp4', 'mov', 'webm', 'avi', 'mkv', 'm4v', '3gp', 'ogv', 'flv'], true)
            ? 'video'
            : 'image';
    }

    /**
     * Post level media_type: image, video, mixed or null.
     */
    protected function postMediaType(array $types): ?string
    {
        if (! $types) {
            return null;
        }

        $unique = array_values(array_unique($types));

        return count($unique) === 1 ? $unique[0] : 'mixed';
    }

    /**
     * Extension taken from the detected mime type, never from client params.
     */
    protected function mediaExtension($file, string $mime): string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/heic' => 'heic',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'video/webm' => 'webm',
            'video/x-matroska' => 'mkv',
            'video/3gpp' => '3gp',
        ];

        if (isset($map[$mime])) {
            return $map[$mime];
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());

        return preg_match('/^[a-z0-9]{1,8}$/', $extension) ? $extension : 'bin';
    }

    /**
     * Video thumbnail: poster uploaded by the app first, ffmpeg as fallback.
     */
    protected function resolveVideoThumbnail($poster, $video, string $videoName): ?string
    {
        if ($poster) {
            $mime = (string) $poster->getMimeType();

            if (! str_starts_with($mime, 'image/') || $poster->getSize() / 1024 / 1024 > 5) {
                throw ValidationException::withMessages([
                    'thumbnails' => 'Each thumbnail must be an image under 5 MB',
                ]);
            }

            $thumbnailName = pathinfo($videoName, PATHINFO_FILENAME) . '.' . $this->mediaExtension($poster, $mime);
            Storage::disk('s3')->putFileAs('media/thumbnails', $poster, $thumbnailName);

            return $thumbnailName;
        }

        return $this->generateVideoThumbnail($video->getPathname(), $videoName);
    }

    /**
     * Extract a frame server side when ffmpeg is installed, otherwise null.
     */
    protected function generateVideoThumbnail(string $videoPath, string $videoName): ?string
    {
        try {
            if (! function_exists('shell_exec')) {
                return null;
            }

            $ffmpeg = trim((string) @shell_exec('command -v ffmpeg 2>/dev/null'));
            if ($ffmpeg === '') {
                return null;
            }

            $thumbnailName = pathinfo($videoName, PATHINFO_FILENAME) . '.jpg';
            $output = tempnam(sys_get_temp_dir(), 'vthumb_') . '.jpg';

            foreach (['00:00:01', '00:00:00'] as $seek) {
                if (is_file($output)) {
                    @unlink($output);
                }

                $command = sprintf(
                    '%s -y -ss %s -i %s -frames:v 1 -vf scale=640:-2 %s 2>/dev/null',
                    escapeshellcmd($ffmpeg),
                    escapeshellarg($seek),
                    escapeshellarg($videoPath),
                    escapeshellarg($output)
                );

                @shell_exec($command);

                if (is_file($output) && filesize($output) > 0) {
                    Storage::disk('s3')->put('media/thumbnails/' . $thumbnailName, (string) file_get_contents($output));
                    @unlink($output);

                    return $thumbnailName;
                }
            }

            if (is_file($output)) {
                @unlink($output);
            }

            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}