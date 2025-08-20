<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_name',
        'variant_actual_price',
        'variant_selling_price',
        'variant_save_price_in_rs',
        'variant_save_price_in_percent',
        'stock',
        'attributes', // JSON field storing selected attributes like {"Color": "Red", "Size": "M"}
        'is_active',
        'is_deleted',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'attributes' => 'array', // Automatically cast JSON to array
    ];

    // ✅ Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
