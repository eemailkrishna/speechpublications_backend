<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone_number',
        'country_code',
        'dob',
        'gender',
        'bio',
        'profile_photo',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'dob' => 'date',
            'is_verified' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has a profile photo
     */
    public function hasProfilePhoto()
    {
        return !empty($this->profile_photo);
    }

    /**
     * Get the user's age
     */
    public function getAgeAttribute()
    {
        if ($this->dob) {
            return $this->dob->age;
        }
        return null;
    }
    public function orders()
{
    return $this->hasMany(Order::class);
}
public function hasRole($role)
{
    return $this->role === $role;
}
public function getRoleAttribute()
{
    return $this->getRoleNames()->first();
}
    /**
     * Users this user follows (following list)
     */
    public function following()
    {
        return $this->belongsToMany(self::class, 'follows', 'follower_id', 'following_id');
    }

    /**
     * Users who follow this user
     */
    public function followers()
    {
        return $this->belongsToMany(self::class, 'follows', 'following_id', 'follower_id');
    }

    /**
     * Alias used by StoryController to get followed ids
     */
    public function follows()
    {
        return $this->following();
    }
    public function isFollowing(User $user)
{
    return $this->following()
        ->where('following_id', $user->id)
        ->exists();
}
}
