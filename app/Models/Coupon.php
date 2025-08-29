<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    protected $table = 'coupons';
    use SoftDeletes;
    protected $fillable = [
        'code', 'discount_type', 'discount_value',
        'min_order_amount', 'max_uses', 'max_uses_per_user',
        'starts_at', 'expires_at',
        'applies_to', 'created_by_type', 'created_by_id',
        'is_active','is_deleted',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'created_by_type' => 'string',
        'created_by_id' => 'integer',
    ];


    protected $dates = ['deleted_at'];

    // Dynamic relation to Admin, Vendor, etc.
    public function creator(): MorphTo
    {
        return $this->morphTo('created_by');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product', 'coupon_id', 'product_id')
            ->where('is_deleted', 0)
            ->where('is_active', 1);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_category', 'coupon_id', 'category_id')
            ->where('is_deleted', 0)
            ->where('is_active', 1);
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'coupon_subcategory', 'coupon_id', 'subcategory_id')
            ->where('is_deleted', 0)
            ->where('is_active', 1);
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }
     public function vendor()
    {
        return $this->belongsTo(VendorAdmin::class);
    }


    public function isValidForUser($user): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->max_uses && $this->usages()->sum('usage_count') >= $this->max_uses) return false;

        $userUsage = $this->usages()->where('user_id', $user->id)->first();
        if ($this->max_uses_per_user && $userUsage && $userUsage->usage_count >= $this->max_uses_per_user) {
            return false;
        }

        return true;
    }

}
