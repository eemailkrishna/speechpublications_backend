<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsView extends Model
{
    use HasFactory;

    protected $table = 'news_views';

    public $timestamps = false;

    protected $fillable = [
        'news_id',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
