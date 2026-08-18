<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'media_url' => $this->media_url,
            'thumbnail_url' => $this->thumbnail_url,
            'caption' => $this->caption,
            'views_count' => $this->views_count,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
        ];
    }
}
