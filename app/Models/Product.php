<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\VendorAdmin;
use App\Models\ProductImages;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'sku',
        'title',
        'sub_title',
        'slug',
        'category_id',
        'sub_category_id',
        'is_physical',
        'description',
        'disclaimer',
        'information',
        'cgst',
        'sgst',
        'feature_image_id',
        'best_offers',
        'top_selling',
        'is_active',
        'is_deleted',
    ];

    protected $casts = [
        'best_offers' => 'boolean',
        'top_selling' => 'boolean',
        'is_active' => 'boolean',
        'is_physical' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    // ✅ Relationships

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id');
    }

    public function vendor()
    {
        return $this->belongsTo(VendorAdmin::class, 'vendor_id');
    }

    public function featureImage()
    {
        return $this->belongsTo(ProductImages::class, 'feature_image_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_product');
    }
    // app/Models/Category.php

public function products()
{
    return $this->hasMany(Product::class, 'category_id');
}


}
