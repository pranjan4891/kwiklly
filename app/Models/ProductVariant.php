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

    /** Multiple images per variant (e.g. per color) - uploaded when adding/editing variant. */
    public function images()
    {
        return $this->hasMany(ProductVariantImage::class, 'variant_id');
    }

    /** Get variant attribute value by name from JSON attributes. e.g. getVariantAttributeByName('Color') => 'Teal'. */
    public function getVariantAttributeByName(string $attributeName): ?string
    {
        $attrs = $this->getAttribute('attributes');
        if (!is_array($attrs)) {
            $attrs = is_string($attrs) ? json_decode($attrs, true) : [];
        }
        return $attrs[$attributeName] ?? null;
    }

    /** First stored image path for this variant (uses eager-loaded `images` when present). */
    public function getFirstImagePath(): ?string
    {
        if ($this->relationLoaded('images')) {
            $path = $this->images->first()?->image_path;
            return $path !== null && $path !== '' ? $path : null;
        }
        $first = $this->images()->first();

        return $first && $first->image_path !== '' ? $first->image_path : null;
    }

    /** Card/list image: variant upload if any, else product feature image, else site default. */
    public function displayImageUrlForProduct(Product $product): string
    {
        $path = $this->getFirstImagePath();
        if ($path !== null && $path !== '') {
            return asset('public/' . ltrim($path, '/'));
        }
        $feature = $product->featureImage;
        if ($feature && ! empty($feature->feature_image)) {
            return asset('public/' . ltrim($feature->feature_image, '/'));
        }

        return asset('public/assets/website/images/default.png');
    }
}
