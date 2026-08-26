<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author_name',
        'rating',
        'description',
        'price',
        'ebook_price',
        'image',
        'ebook_pdf',
        'is_ebook',
        'slug',
        'heading',
        'specification',
        'inside_the_box',
        'category_id',
        'status',
        
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    
}

