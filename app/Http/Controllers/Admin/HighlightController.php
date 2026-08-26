<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class HighlightController extends Controller
{
    public function index()
    {
        $highlights = News::where('is_highlight', true)->orderBy('publish_date', 'desc')->get();
        $newsList = News::orderBy('title')->get();
        return view('admin.highlights.index', compact('highlights', 'newsList'));
    }

    public function store(Request $request)
    {
        $request->validate(['news_id' => 'required|exists:news,id']);
        $news = News::find($request->news_id);
        $news->is_highlight = true;
        $news->save();
        return redirect()->route('admin.highlights.index')->with('success', 'News added to highlights.');
    }

    public function destroy(News $highlight)
    {
        $highlight->is_highlight = false;
        $highlight->save();
        return redirect()->route('admin.highlights.index')->with('success', 'Removed from highlights.');
    }
}
