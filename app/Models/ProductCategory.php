<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductCategory extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'name',
        'slug',
        'image',
        'asc_id'
    ];

    public function getImageAttribute($value)
{
    return $value
        ? Storage::disk('s3')->url('product-category/'.$value)
        : null;
}
}
