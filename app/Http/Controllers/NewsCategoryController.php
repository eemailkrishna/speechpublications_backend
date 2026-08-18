<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categories = NewsCategory::withCount('news')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('admin.news-category.list', compact('categories', 'search'));
    }

    public function create()
    {
        return view('admin.news-category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug, '-')
            : $this->uniqueSlug($request->name);

        if (NewsCategory::where('slug', $slug)->exists()) {
            return back()->withInput()->withErrors(['slug' => 'This slug already exists. Please use a different one.']);
        }

        NewsCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->has('status') ? 'active' : 'inactive',
        ]);

        return redirect()->route('admin-news-category.index')->with('success', 'Category added successfully!');
    }

    public function edit($id)
    {
        $category = NewsCategory::findOrFail($id);
        return view('admin.news-category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = NewsCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug, '-')
            : $this->uniqueSlug($request->name, $category->id);

        if (NewsCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            return back()->withInput()->withErrors(['slug' => 'This slug already exists. Please use a different one.']);
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->has('status') ? 'active' : 'inactive',
        ]);

        return redirect()->route('admin-news-category.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = NewsCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin-news-category.index')->with('success', 'Category deleted successfully!');
    }

    protected function uniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name, '-');
        $baseSlug = $slug;
        $count = 1;

        while (NewsCategory::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        return $slug;
    }
}
