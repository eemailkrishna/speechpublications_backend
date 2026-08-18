<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'email',
        'phone_number',
        'country_code',
        'otp',
        'expires_at',
        'is_verified',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];
}
