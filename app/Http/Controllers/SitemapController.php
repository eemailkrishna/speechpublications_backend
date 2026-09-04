<?php

namespace App\Http\Controllers;

use App\Models\Sitemap;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemaps = Sitemap::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.sitemap.list', compact('sitemaps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2000',
            'priority' => 'required|numeric|min:0|max:1',
            'changefreq' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
        ]);

        Sitemap::create([
            'url' => $request->url,
            'priority' => $request->priority,
            'changefreq' => $request->changefreq,
            'is_active' => true,
        ]);

        return back()->with('success', 'Sitemap URL added successfully!');
    }

    public function destroy($id)
    {
        Sitemap::findOrFail($id)->delete();
        return back()->with('success', 'Sitemap URL deleted successfully!');
    }

    public function toggle($id)
    {
        $sitemap = Sitemap::findOrFail($id);
        $sitemap->is_active = !$sitemap->is_active;
        $sitemap->save();
        return back()->with('success', 'Sitemap URL status updated!');
    }
}
