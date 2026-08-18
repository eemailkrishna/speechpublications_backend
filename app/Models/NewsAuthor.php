<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsAuthor extends Model
{
    use HasFactory;

    public function getProfileImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset('uploads/news/authors/' . $value);
    }

    protected $table = 'news_authors';

    protected $fillable = [
        'full_name',
        'slug',
        'email',
        'profile_image',
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
        'status',
    ];

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getSocialProfilesAttribute()
    {
        return array_filter([
            'facebook' => $this->facebook_url,
            'twitter' => $this->twitter_url,
            'linkedin' => $this->linkedin_url,
            'instagram' => $this->instagram_url,
        ]);
    }
}
