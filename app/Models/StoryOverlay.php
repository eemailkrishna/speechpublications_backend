<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryOverlay extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'type',
        'content',
        'x_offset',
        'y_offset',
        'color',
        'font_size',
        'size',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}