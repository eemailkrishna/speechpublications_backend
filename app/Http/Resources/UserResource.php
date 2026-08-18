<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $authenticatedUser = auth('api')->user();
        $isFollowing = false;

        if ($authenticatedUser && $authenticatedUser->id !== $this->id) {
            $isFollowing = $authenticatedUser->isFollowing($this);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'bio' => $this->bio,
            'phone_number' => $this->phone_number,
            'profile_photo' => $this->profile_photo ? \Illuminate\Support\Facades\Storage::disk('s3')->url('profile/'.$this->profile_photo) : null,
            'followers_count' => $this->followers_count,
            'following_count' => $this->following_count,
            'posts_count' => $this->posts_count,
            'is_following' => $isFollowing,
            'is_verified' => (bool) $this->is_verified,
            'created_at' => $this->created_at
                ? $this->created_at->format('Y-m-d H:i:s')
                : null,
        ];
    }
}
