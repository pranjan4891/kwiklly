<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    use HasFactory;

    protected $table = 'product_images';

    protected $fillable = [
        'product_name', 'brand_name', 'description', 'feature_image', 'product_images', 'is_active', 'is_deleted',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    /**
     * Safe JSON/array handling so legacy or invalid DB values never break updates (array_merge, foreach).
     */
    protected function productImages(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value === null || $value === '') {
                    return [];
                }
                if (is_array($value)) {
                    return array_values(array_filter($value));
                }
                $decoded = json_decode((string) $value, true);

                return is_array($decoded) ? array_values(array_filter($decoded)) : [];
            },
            set: function ($value) {
                if ($value === null || $value === '' || $value === []) {
                    return json_encode([]);
                }
                if (is_array($value)) {
                    return json_encode(array_values(array_filter($value)));
                }

                return $value;
            },
        );
    }

    /**
     * URL for files stored under public/ (same convention as product_images blades).
     */
    public static function publicAssetUrl(?string $storedPath): string
    {
        if ($storedPath === null || $storedPath === '') {
            return '';
        }

        return asset('public/'.ltrim($storedPath, '/'));
    }
}
