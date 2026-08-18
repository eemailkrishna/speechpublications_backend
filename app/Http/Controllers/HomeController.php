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

use Mail;
use validated;


class HomeController extends Controller
{
    public function index(){
        
        $testimonials = Testimonial::all();
        
        return view('home',compact('testimonials'));
    }

    public function news(Request $request){

        $categoryId = $request->get('category_id');
        $search = $request->get('search');

        $featured = News::with(['author', 'category'])
            ->published()
            ->featured()
            ->whereHas('author', fn($q) => $q->where('status', 'active'))
            ->whereHas('category', fn($q) => $q->where('status', 'active'))
            ->latest('publish_date')
            ->first();

        $news = News::with(['author', 'category'])
            ->published()
            ->whereHas('author', fn($q) => $q->where('status', 'active'))
            ->whereHas('category', fn($q) => $q->where('status', 'active'))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($search, fn($q) => $q->search($search))
            ->latest('publish_date')
            ->take(9)
            ->get();

        $total = News::published()
            ->whereHas('author', fn($q) => $q->where('status', 'active'))
            ->whereHas('category', fn($q) => $q->where('status', 'active'))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($search, fn($q) => $q->search($search))
            ->count();

        $categories = NewsCategory::active()
            ->withCount(['news' => fn($q) => $q->published()])
            ->orderBy('name')
            ->get();

        $recentPosts = News::published()->orderBy('publish_date', 'desc')->take(4)->get();
        $trendingPosts = News::published()->orderBy('view_count', 'desc')->orderBy('publish_date', 'desc')->take(4)->get();

        // Return JSON for AJAX requests
        if ($request->ajax()) {
            $html = '';
            foreach ($news as $item) {
                $html .= view('news.partials.card', ['item' => $item])->render();
            }
            return response()->json([
                'html' => $html,
                'hasMore' => $news->count() >= 9 && $news->count() < $total,
            ]);
        }

        $data = compact('featured', 'news', 'categories', 'recentPosts', 'trendingPosts', 'categoryId', 'search', 'total');
        return view('news.news', $data);
    }
 
}
