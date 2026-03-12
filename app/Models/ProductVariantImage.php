<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    use HasFactory;

    protected $fillable = ['variant_id', 'image_path'];
    protected $table = 'product_variant_images';

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
