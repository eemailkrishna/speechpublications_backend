<?php

namespace App\Http\Controllers;

use App\Models\NewsAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsAuthorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $authors = NewsAuthor::when($search, function ($query) use ($search) {
            return $query->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('designation', 'like', "%{$search}%");
        })
            ->orderBy('full_name')
            ->paginate(10);

        return view('admin.news-author.list', compact('authors', 'search'));
    }

    public function create()
    {
        return view('admin.news-author.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:news_authors,email',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'language' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        $data = $request->only([
            'full_name',
            'email',
            'location',
            'phone',
            'language',
            'designation',
            'bio',
            'specialization',
            'facebook_url',
            'twitter_url',
            'linkedin_url',
            'instagram_url',
        ]);

        $data['slug'] = $this->uniqueSlug($request->full_name);
        $data['status'] = $request->has('status') ? 'active' : 'inactive';

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $this->uploadImage($request->file('profile_image'));
        }

        NewsAuthor::create($data);

        return redirect()->route('admin-news-author.index')->with('success', 'Author added successfully!');
    }

    public function edit($id)
    {
        $author = NewsAuthor::findOrFail($id);
        return view('admin.news-author.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $author = NewsAuthor::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:news_authors,email,' . $author->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'language' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        $data = $request->only([
            'full_name',
            'email',
            'location',
            'phone',
            'language',
            'designation',
            'bio',
            'specialization',
            'facebook_url',
            'twitter_url',
            'linkedin_url',
            'instagram_url',
        ]);

        if ($request->full_name !== $author->full_name) {
            $data['slug'] = $this->uniqueSlug($request->full_name, $author->id);
        }

        $data['status'] = $request->has('status') ? 'active' : 'inactive';

        if ($request->hasFile('profile_image')) {
            if ($author->profile_image) {
                $this->deleteImage($author->profile_image);
            }
            $data['profile_image'] = $this->uploadImage($request->file('profile_image'));
        }

        $author->update($data);

        return redirect()->route('admin-news-author.index')->with('success', 'Author updated successfully!');
    }

    public function destroy($id)
    {
        $author = NewsAuthor::findOrFail($id);

        if ($author->profile_image) {
            $this->deleteImage($author->profile_image);
        }

        $author->delete();

        return redirect()->route('admin-news-author.index')->with('success', 'Author deleted successfully!');
    }

    protected function uploadImage($file)
    {
        $name = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('s3')->putFileAs('uploads/news/authors', $file, $name);
        return Storage::disk('s3')->url($path);
    }

    protected function uniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name, '-');
        $baseSlug = $slug;
        $count = 1;

        while (NewsAuthor::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        return $slug;
    }

    protected function deleteImage($value)
    {
        if (!$value) {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $path = ltrim((string) parse_url($value, PHP_URL_PATH), '/');
        } else {
            $path = 'uploads/news/authors/' . $value;
        }

        if ($path) {
            Storage::disk('s3')->delete($path);
        }

        $local = public_path($path);
        if ($local && file_exists($local)) {
            unlink($local);
        }
    }
}
