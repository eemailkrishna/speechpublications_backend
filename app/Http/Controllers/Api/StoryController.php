<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\StoryOverlay;
use App\Models\StoryView;
use App\Http\Resources\StoryResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
   
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // own stories and followed users' stories
        $followedIds = $user->follows()->pluck('following_id')->toArray();

        $stories = Story::with('user','overlays')
            ->where(function($q) use ($user, $followedIds) {
                $q->where('user_id', $user->id)
                  ->orWhereIn('user_id', $followedIds);
            })->where('expires_at', '>', now())
              ->orderBy('created_at', 'desc')
              ->get();

        // convert stored filename to full S3 URL for response
        $stories->each(function($s){
            if ($s->media_url) {
                $s->media_url = Storage::disk('s3')->url('stories/'.ltrim($s->media_url, '/'));
            }
        });

        // group by user
        $groups = $stories->groupBy('user_id')->map(function($group, $userId) use ($user) {
            $first = $group->first();
            $userModel = $first->user;

        $items = $group->values()->map(function ($s, $index) use ($user) {
                $hasSeen = StoryView::where('story_id', $s->id)
                    ->where('viewer_id', $user->id)
                    ->exists();

                $likesCount = StoryView::where('story_id', $s->id)
                    ->where('is_like', true)
                    ->count();

                $isLike = StoryView::where('story_id', $s->id)
                    ->where('viewer_id', $user->id)
                    ->where('is_like', true)
                    ->exists();

                return [
                    'id' => $s->id,
                    'order' => $index + 1,
                    'type' => $s->type,
                    'url' => $s->media_url,
                    'caption' => $s->caption ?? '',
                    'seen' => (bool) $hasSeen,
                    'timeAgo' => optional($s->created_at)->diffForHumans(),
                    'views' => $s->views_count ?? 0,
                    'likes' => $likesCount,
                    'isLike' => $isLike,
                    'overlays' => $s->overlays->map(function ($overlay) {
                        return [
                            'type' => $overlay->type,
                            'content' => $overlay->content,
                            'x_offset' => (float) $overlay->x_offset,
                            'y_offset' => (float) $overlay->y_offset,
                            'color' => $overlay->color,
                            'font_size' => $overlay->font_size,
                            'size' => $overlay->size,
                        ];
                    })->values(),
                    'created_at' => optional($s->created_at)->toISOString(),
                    'expires_at' => optional($s->expires_at)->toISOString(),
                ];
                            })->values();

            return [
                'id' => (int) $userId,
                'is_own' => $user->id == $userId,
                'has_unseen' => $items->contains(function($it){ return !$it['seen']; }),
                'story_count' => $items->count(),
                'user' => [
                    'id' => $userModel->id,
                    'name' => $userModel->name,
                    'username' => $userModel->username ?? null,
                    'profile_photo' => $userModel->profile_photo ? Storage::disk('s3')->url('profile/'.ltrim($userModel->profile_photo, '/')) : null,
                ],
                'items' => $items,
            ];
        })->values();

        return response()->json(['success' => true, 'data' => $groups]);
    }

   public function store(Request $request)
{
    $user = auth('api')->user();

    $request->validate([
        'media' => 'required|array',
        'media.*.media_path' => 'required|file',
        'media.*.media_type' => 'required|in:image,video',
        'media.*.caption' => 'nullable|string',
        'media.*.overlays' => 'nullable|string'
    ]);

    $stories = [];

    foreach ($request->file('media') as $index => $mediaItem) {
        // Get the file from media[index][media_path]
        $file = $request->file("media.{$index}.media_path");
        $mediaType = $request->input("media.{$index}.media_type");
        $caption = $request->input("media.{$index}.caption");
        $overlaysData = $request->input("media.{$index}.overlays");

        $fileName = uniqid('', true) . '.' . $file->getClientOriginalExtension();

        Storage::disk('s3')->putFileAs(
            'stories',
            $file,
            $fileName
        );

        $url = Storage::disk('s3')->url('stories/' . $fileName);

        $story = Story::create([
            'user_id' => $user->id,
            'type' => $mediaType,
            'media_url' => $fileName,
            'thumbnail_url' => null,
            'caption' => $caption,
            'expires_at' => now()->addDay(),
        ]);

        $story->media_url = $url;

        $stories[] = $story;

        if ($overlaysData) {
            try {
                $cleanData = $overlaysData;
                
                // First, decode all HTML entities completely
                $cleanData = html_entity_decode($cleanData, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                // Remove escape slashes before quotes - this is the key fix!
                $cleanData = str_replace('\\"', '"', $cleanData);
                $cleanData = str_replace('\\#', '#', $cleanData);
                $cleanData = str_replace('\\/', '/', $cleanData);
                
                // Parse JSON
                $overlays = json_decode($cleanData, true);
                
                if (is_array($overlays) && !empty($overlays)) {
                    foreach ($overlays as $overlay) {
                        \App\Models\StoryOverlay::create([
                            'story_id'   => $story->id,
                            'type'       => $overlay['type'] ?? null,
                            'content'    => $overlay['content'] ?? null,
                            'x_offset'   => $overlay['x_offset'] ?? null,
                            'y_offset'   => $overlay['y_offset'] ?? null,
                            'color'      => $overlay['color'] ?? null,
                            'font_size'  => $overlay['font_size'] ?? null,
                            'size'       => $overlay['size'] ?? null,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                // Log error if JSON decode fails
                \Log::error('Overlay JSON decode error: ' . $e->getMessage());
            }
        }
    }

    return response()->json([
        'success' => true,
        'count' => count($stories),
        'data' => StoryResource::collection(collect($stories))
    ], 201);
}

    public function view(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        $story = Story::findOrFail($id);

        // only active stories
        if ($story->expires_at <= now()) {
            return response()->json(['success'=>false,'message'=>'Story expired'], 410);
        }

        return $this->markStoryView($request, $story, $user);
    }

    public function itemView(Request $request, $storyId)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $story = Story::findOrFail($storyId);

        if ($story->expires_at <= now()) {
            return response()->json(['success'=>false,'message'=>'Story expired'], 410);
        }

        return $this->markStoryView($request, $story, $user);
    }

    public function itemViewers($storyId)
    {
        $story = Story::findOrFail($storyId);

        $viewers = $story->views()->with('viewer')->orderByDesc('viewed_at')->get()->map(function ($view) {
            $viewer = $view->viewer;
            if (!$viewer) {
                return null;
            }

            $profilePhoto = $viewer->profile_photo;
            if ($profilePhoto && !str_starts_with($profilePhoto, 'http')) {
                try {
                    $profilePhoto = Storage::disk('s3')->url('profile/' . ltrim($profilePhoto, '/'));
                } catch (\Throwable $e) {
                    $profilePhoto = $viewer->profile_photo;
                }
            }

            $viewedAt = $view->viewed_at ?: $view->created_at ?: now();

            return [
                'id' => $view->id,
                'user' => [
                    'id' => $viewer->id,
                    'name' => $viewer->name,
                    'username' => $viewer->username ?? null,
                    'profile_photo' => $profilePhoto,
                ],
                'timeAgo' => $viewedAt instanceof \Carbon\Carbon ? $viewedAt->diffForHumans() : \Carbon\Carbon::parse($viewedAt)->diffForHumans(),
                'isLike' => (bool) ($view->is_like ?? false),
            ];
        })->filter()->values();

        return response()->json(['success' => true, 'data' => $viewers]);
    }

    public function itemLike(Request $request, $storyId)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'is_like' => 'required|boolean',
        ]);

        $story = Story::findOrFail($storyId);
        $view = StoryView::firstOrCreate(
            ['story_id' => $story->id, 'viewer_id' => $user->id],
            ['viewed_at' => now(), 'is_like' => false]
        );

        $view->update(['is_like' => (bool) $request->input('is_like')]);

        return response()->json(['success' => true]);
    }

    public function itemDelete($storyId)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $story = Story::findOrFail($storyId);
        if ($story->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        try {
            if ($story->media_url) {
                $key = 'media/stories/' . ltrim($story->media_url, '/');
                if (Storage::disk('s3')->exists($key)) {
                    Storage::disk('s3')->delete($key);
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $story->delete();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        $story = Story::findOrFail($id);
        if ($story->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json(['success'=>false,'message'=>'Forbidden'],403);
        }

        // delete media from s3 if stored as path
        try {
            if ($story->media_url) {
                $key = 'media/stories/'.ltrim($story->media_url, '/');
                if (Storage::disk('s3')->exists($key)) {
                    Storage::disk('s3')->delete($key);
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $story->delete();
        return response()->json(['success'=>true]);
    }

    public function viewers($id)
    {
        $story = Story::findOrFail($id);

        $viewers = $story->views()->with('viewer')->orderByDesc('viewed_at')->get()->map(function ($view) {
            $viewer = $view->viewer;
            if (!$viewer) {
                return null;
            }

            $profilePhoto = $viewer->profile_photo;
            if ($profilePhoto && !str_starts_with($profilePhoto, 'http')) {
                try {
                    $profilePhoto = Storage::disk('s3')->url('profile/' . ltrim($profilePhoto, '/'));
                } catch (\Throwable $e) {
                    $profilePhoto = $viewer->profile_photo;
                }
            }

            $viewedAt = $view->viewed_at ?: $view->created_at ?: now();

            return [
                'id' => $view->id,
                'user' => [
                    'id' => $viewer->id,
                    'name' => $viewer->name,
                    'username' => $viewer->username ?? null,
                    'profile_photo' => $profilePhoto,
                ],
                'timeAgo' => $viewedAt instanceof \Carbon\Carbon ? $viewedAt->diffForHumans() : \Carbon\Carbon::parse($viewedAt)->diffForHumans(),
                'isLike' => (bool) ($view->is_like ?? false),
            ];
        })->filter()->values();

        return response()->json(['success' => true, 'data' => $viewers]);
    }

    private function markStoryView(Request $request, Story $story, $user)
    {
        $existing = StoryView::where('story_id', $story->id)
            ->where('viewer_id', $user->id)
            ->first();

        if (!$existing) {
            StoryView::create([
                'story_id' => $story->id,
                'viewer_id' => $user->id,
                'is_like' => $request->input('is_like', false),
                'viewed_at' => now(),
            ]);

            $story->increment('views_count');
        } else {
            $existing->update([
                'is_like' => $request->input('is_like', $existing->is_like),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
