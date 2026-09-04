<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsAuthor;
use App\Models\NewsCategory;
use App\Models\NewsComment;
use App\Models\NewsView;
use Illuminate\Http\Request;

class NewsFrontController extends Controller
{
    public function show($slug)
    {
        $news = News::with(['author', 'category', 'approvedComments.user'])
            ->published()
            ->whereHas('author', fn($q) => $q->where('status', 'active'))
            ->whereHas('category', fn($q) => $q->where('status', 'active'))
            ->where('slug', $slug)
            ->firstOrFail();

        $this->recordView($news);

        $related = $this->relatedNews($news);
        $categories = $this->categoriesWithCount();
        $recentPosts = $this->recentPosts();
        $trendingPosts = $this->trendingPosts();
        $comments = $news->approvedComments()->orderBy('created_at', 'desc')->paginate(10);

        $metaTitle = $news->title . ' - Speech Publications';
        $metaDescription = \Illuminate\Support\Str::limit(strip_tags($news->excerpt ?? $news->description), 160);
        $metaImage = $news->featured_image ?? asset('images/logo.png');

        return view('news.details', compact(
            'news',
            'related',
            'categories',
            'recentPosts',
            'trendingPosts',
            'comments',
            'metaTitle',
            'metaDescription',
            'metaImage'
        ));
    }

    public function author($slug)
    {
        $author = NewsAuthor::active()->where('slug', $slug)->firstOrFail();

        $articles = News::with(['author', 'category'])
            ->published()
            ->where('author_id', $author->id)
            ->orderBy('publish_date', 'desc')
            ->paginate(9);

        $categories = $this->categoriesWithCount();
        $recentPosts = $this->recentPosts();
        $trendingPosts = $this->trendingPosts();

        $metaTitle = ($author->full_name ?? 'Author') . ' - Speech Publications';
        $metaDescription = 'Articles by ' . ($author->full_name ?? 'Author') . ' on Speech Publications.';
        $metaImage = $author->image ?? asset('images/logo.png');

        return view('news.author', compact(
            'author',
            'articles',
            'categories',
            'recentPosts',
            'trendingPosts',
            'metaTitle',
            'metaDescription',
            'metaImage'
        ));
    }

    public function category($slug)
    {
        $category = NewsCategory::active()->where('slug', $slug)->firstOrFail();

        $news = News::with(['author', 'category'])
            ->published()
            ->where('category_id', $category->id)
            ->orderBy('publish_date', 'desc')
            ->paginate(9);

        $total = $news->total();
        $categoryId = $category->id;
        $search = '';

        $categories = $this->categoriesWithCount();
        $recentPosts = $this->recentPosts();
        $trendingPosts = $this->trendingPosts();

        $metaTitle = ($category->name ?? 'Category') . ' - Speech Publications';
        $metaDescription = 'Browse articles in ' . ($category->name ?? 'Category') . ' on Speech Publications.';
        $metaImage = asset('images/logo.png');

        return view('news.news', compact(
            'news',
            'categories',
            'recentPosts',
            'trendingPosts',
            'category',
            'categoryId',
            'search',
            'total',
            'metaTitle',
            'metaDescription',
            'metaImage'
        ));
    }

    public function loadMore(Request $request)
    {
        $offset = (int) $request->get('offset', 9);
        $categoryId = $request->get('category_id');
        $search = $request->get('search');

        $news = News::with(['author', 'category'])
            ->published()
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($search, fn($q) => $q->search($search))
            ->orderBy('publish_date', 'desc')
            ->skip($offset)
            ->take(9)
            ->get();

        $html = '';
        foreach ($news as $item) {
            $html .= view('news.partials.card', ['item' => $item])->render();
        }

        return response()->json([
            'html' => $html,
            'hasMore' => $news->count() === 9,
        ]);
    }

    public function comment(Request $request)
    {
        $request->validate([
            'news_id' => 'required|exists:news,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        NewsComment::create([
            'news_id' => $request->news_id,
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'status' => 'approved',
        ]);

        return back()->with('success', 'Comment posted successfully!');
    }

    protected function recordView(News $news)
    {
        $ip = request()->ip();

        $exists = NewsView::where('news_id', $news->id)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subHours(1))
            ->exists();

        if (!$exists) {
            NewsView::create([
                'news_id' => $news->id,
                'ip_address' => $ip,
                'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
                'created_at' => now(),
            ]);
            $news->increment('view_count');
        }
    }

    protected function relatedNews(News $news)
    {
        $related = News::with(['author', 'category'])
            ->published()
            ->where('id', '!=', $news->id)
            ->when($news->category_id, fn($q) => $q->where('category_id', $news->category_id))
            ->orderBy('publish_date', 'desc')
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $related = News::with(['author', 'category'])
                ->published()
                ->where('id', '!=', $news->id)
                ->orderBy('publish_date', 'desc')
                ->take(3)
                ->get();
        }

        return $related;
    }

    protected function categoriesWithCount()
    {
        return NewsCategory::active()
            ->withCount(['news' => fn($q) => $q->published()])
            ->orderBy('name')
            ->get();
    }

    protected function recentPosts()
    {
        return News::with(['author', 'category'])
            ->published()
            ->orderBy('publish_date', 'desc')
            ->take(4)
            ->get();
    }

    protected function trendingPosts()
    {
        return News::with(['author', 'category'])
            ->published()
            ->orderBy('view_count', 'desc')
            ->orderBy('publish_date', 'desc')
            ->take(4)
            ->get();
    }
}
