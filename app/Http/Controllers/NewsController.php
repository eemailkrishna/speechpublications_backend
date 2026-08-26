<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsAuthor;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $news = News::with(['author', 'category'])
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.news.list', compact('news', 'search'));
    }

    public function create()
    {
        $authors = NewsAuthor::active()->orderBy('full_name')->get();
        $categories = NewsCategory::active()->orderBy('name')->get();
        return view('admin.news.create', compact('authors', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:news_categories,id',
            'author_id' => 'nullable|exists:news_authors,id',
            'description' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'publish_date' => 'nullable|date',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->only([
            'title',
            'category_id',
            'author_id',
            'description',
            'excerpt',
            'publish_date',
            'status',
            'featured',
            'meta_title',
            'meta_description',
        ]);

        $data['slug'] = $this->uniqueSlug($request->title);
        $data['excerpt'] = $request->filled('excerpt')
            ? $request->excerpt
            : Str::limit(strip_tags($request->description), 200);
        $data['reading_time'] = $this->readingTime($request->description);
        $data['featured'] = $request->has('featured') ? 1 : 0;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        $news = News::create($data);

        if ($news->featured) {
            $this->clearOtherFeatured($news->id);
        }

        return redirect()->route('admin-news.index')->with('success', 'News added successfully!');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $authors = NewsAuthor::active()->orderBy('full_name')->get();
        $categories = NewsCategory::active()->orderBy('name')->get();
        return view('admin.news.edit', compact('news', 'authors', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:news_categories,id',
            'author_id' => 'nullable|exists:news_authors,id',
            'description' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'publish_date' => 'nullable|date',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->only([
            'title',
            'category_id',
            'author_id',
            'description',
            'excerpt',
            'publish_date',
            'status',
            'featured',
            'meta_title',
            'meta_description',
        ]);

        if ($request->title !== $news->title) {
            $data['slug'] = $this->uniqueSlug($request->title, $news->id);
        }

        $data['excerpt'] = $request->filled('excerpt')
            ? $request->excerpt
            : Str::limit(strip_tags($request->description), 200);
        $data['reading_time'] = $this->readingTime($request->description);
        $data['featured'] = $request->has('featured') ? 1 : 0;

        if ($request->hasFile('featured_image')) {
            if ($news->featured_image) {
                $this->deleteImage($news->featured_image);
            }
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        $news->update($data);

        if ($news->featured) {
            $this->clearOtherFeatured($news->id);
        }

        return redirect()->route('admin-news.index')->with('success', 'News updated successfully!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->featured_image) {
            $this->deleteImage($news->featured_image);
        }

        $news->delete();

        return redirect()->route('admin-news.index')->with('success', 'News deleted successfully!');
    }

    public function uploadEditorImage(Request $request)
    {
        try {
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            ]);

            $url = $this->uploadImage($request->file('upload'));

            return response()->json([
                'url' => $url,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Invalid file. Only images up to 4MB are allowed.'], 422);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Image upload failed. Please try again.'], 500);
        }
    }

    protected function uniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title, '-');
        $baseSlug = $slug;
        $count = 1;

        while (News::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        return $slug;
    }

    protected function uploadImage($file)
    {
        $name = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('s3')->putFileAs('uploads/news', $file, $name);
        return Storage::disk('s3')->url($path);
    }

    protected function deleteImage($value)
    {
        if (!$value) {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $path = ltrim((string) parse_url($value, PHP_URL_PATH), '/');
        } else {
            $path = 'uploads/news/' . $value;
        }

        if ($path) {
            Storage::disk('s3')->delete($path);
        }

        $local = public_path($path);
        if ($local && file_exists($local)) {
            unlink($local);
        }
    }

    protected function readingTime($html)
    {
        $words = str_word_count(strip_tags($html));
        return max(1, (int) ceil($words / 200));
    }

    protected function clearOtherFeatured($newsId)
    {
        News::where('featured', true)
            ->where('id', '!=', $newsId)
            ->update(['featured' => false]);
    }

    public function toggleHighlight(Request $request, $id)
    {
        try {
            $news = News::findOrFail($id);
            $new = ! (bool) $news->is_highlight;
            $news->update(['is_highlight' => $new]);

            return response()->json([
                'success' => true,
                'is_highlight' => (bool) $new,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
