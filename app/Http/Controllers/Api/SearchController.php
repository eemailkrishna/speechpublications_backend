<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // API 21: Search
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:1|max:100',
            'type' => 'nullable|in:all,users,posts,hashtags',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $query = $validated['query'];
        $searchType = $validated['type'] ?? 'all';
        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 20;
        $currentUserId = auth('api')->id();

        $results = [
            'users' => [],
            'posts' => [],
            'hashtags' => [],
        ];

        // Search users
        if ($searchType === 'all' || $searchType == 'users') {
            $users = User::where('name', 'like', "%$query%")
                ->orWhere('username', 'like', "%$query%")
                ->paginate($limit, ['*'], 'page', $page);

            $results['users'] = $users->map(function ($user) use ($currentUserId) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'profile_photo' => $user->profile_photo,
                    'followers_count' => $user->followers_count,
                    'is_following' => $user->id === $currentUserId ? false : 
                                     User::find($currentUserId)->isFollowing($user),
                ];
            })->toArray();
        }

        // Search posts
        if ($searchType === 'all' || $searchType === 'posts') {
            $posts = Post::with('user')
                ->whereHas('user')
                ->where('visibility', 'public')
                ->where('content', 'like', "%$query%")
                ->paginate($limit, ['*'], 'page', $page);

            $results['posts'] = $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'user' => [
                        'full_name' => $post->user->full_name,
                        'username' => $post->user->username,
                    ],
                    'content' => $post->content,
                    'thumbnail' => $post->media_urls ? $post->media_urls[0] : null,
                    'likes_count' => $post->likes_count,
                ];
            })->toArray();
        }

        // Search hashtags (from post content)
        if ($searchType === 'all' || $searchType === 'hashtags') {
            $hashtags = [];
            
            // Extract hashtags from posts containing query
            $posts = Post::where('visibility', 'public')
                ->where('content', 'like', "%#$query%")
                ->pluck('content')
                ->toArray();

            foreach ($posts as $content) {
                preg_match_all('/#([a-zA-Z0-9_]+)/i', $content, $matches);
                foreach ($matches[1] as $tag) {
                    if (stripos($tag, $query) !== false) {
                        if (!isset($hashtags[$tag])) {
                            $hashtags[$tag] = 0;
                        }
                        $hashtags[$tag]++;
                    }
                }
            }

            // Convert to array format
            $results['hashtags'] = array_map(function ($tag, $count) {
                return [
                    'tag' => '#' . $tag,
                    'posts_count' => $count,
                ];
            }, array_keys($hashtags), array_values($hashtags));

            // Limit hashtags
            $results['hashtags'] = array_slice($results['hashtags'], 0, $limit);
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }
}
