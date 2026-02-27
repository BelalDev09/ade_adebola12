<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'price',
        'stock',
        'description',
        'slug',
        'medias',
        'views',
        'status',
    ];

    // Vendor (User with Vendor role)
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
