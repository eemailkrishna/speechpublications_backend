<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Testimonial;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Product;

use Mail;
use validated;


class HomeController extends Controller
{
    public function index(){
        $testimonials = Testimonial::all();

        // Highlights: news items marked as is_highlight
        $highlights = News::where('is_highlight', true)
            ->where('status', 'published')
            ->orderBy('publish_date', 'desc')
            ->get();

        // Popular books: products with is_popular flag
        $popularBooks = Product::where('is_popular', true)
            ->orderBy('name')
            ->get();

        $metaTitle = 'Speech Publications - Books, News & More';
        $metaDescription = 'Speech Publications offers a wide range of books, news articles, and publications. Explore our collection today.';
        $metaImage = asset('images/logo.png');

        return view('home', compact('testimonials', 'highlights', 'popularBooks', 'metaTitle', 'metaDescription', 'metaImage'));
    }

    public function news(Request $request){

        $categoryId = $request->get('category_id');
        $search = $request->get('search');

        $featured = News::with(['author', 'category'])
            ->published()
            ->featured()
            ->where('slug', '!=', '')
            ->whereNotNull('slug')
            ->whereHas('author', fn($q) => $q->where('status', 'active'))
            ->whereHas('category', fn($q) => $q->where('status', 'active'))
            ->latest('publish_date')
            ->first();

        $news = News::with(['author', 'category'])
            ->published()
            ->where('slug', '!=', '')
            ->whereNotNull('slug')
            ->whereHas('author', fn($q) => $q->where('status', 'active'))
            ->whereHas('category', fn($q) => $q->where('status', 'active'))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($search, fn($q) => $q->search($search))
            ->latest('publish_date')
            ->paginate(9)
            ->withQueryString();

        $total = $news->total();

        $categories = NewsCategory::active()
            ->withCount(['news' => fn($q) => $q->published()])
            ->orderBy('name')
            ->get();

        $recentPosts = News::published()->where('slug', '!=', '')->whereNotNull('slug')->orderBy('publish_date', 'desc')->take(4)->get();
        $trendingPosts = News::published()->where('slug', '!=', '')->whereNotNull('slug')->orderByRaw('(view_count + admin_view) desc')->orderBy('publish_date', 'desc')->take(4)->get();

        $metaTitle = 'News - Speech Publications';
        $metaDescription = 'Stay updated with the latest news and articles from Speech Publications.';
        $metaImage = asset('images/logo.png');

        $data = compact('featured', 'news', 'categories', 'recentPosts', 'trendingPosts', 'categoryId', 'search', 'total', 'metaTitle', 'metaDescription', 'metaImage');
        return view('news.news', $data);
    }
 
}
