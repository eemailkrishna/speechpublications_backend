<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'description',
        'excerpt',
        'featured_image',
        'publish_date',
        'featured',
        'reading_time',
        'view_count',
        'status',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'featured' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(NewsAuthor::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class, 'news_id');
    }

    public function approvedComments()
    {
        return $this->hasMany(NewsComment::class, 'news_id')->where('status', 'approved');
    }

    public function views()
    {
        return $this->hasMany(NewsView::class, 'news_id');
    }

    public function scopePublished($query)
    {
        return $query
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('publish_date')
                    ->orWhere('publish_date', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhereHas('author', function ($author) use ($term) {
                    $author->where('full_name', 'like', "%{$term}%");
                })
                ->orWhereHas('category', function ($category) use ($term) {
                    $category->where('name', 'like', "%{$term}%");
                });
        });
    }

    public function getFeaturedImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset('uploads/news/' . $value);
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return $this->featured_image;
        }
        return asset('store/assets/img/news/post-1.jpg');
    }
}
