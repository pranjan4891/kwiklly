<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    use HasFactory;

    protected $table = 'product_images';
    protected $fillable = [
        'product_name', 'brand_name', 'description', 'feature_image', 'product_images', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'product_images' => 'array', // Cast the product_images column to an array
    ];
}
