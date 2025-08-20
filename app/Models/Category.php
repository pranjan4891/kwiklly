<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'va_id',
        'name',
        'slug',
        'image',
        'is_active',
        'is_deleted',
    ];

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_category');
    }

    public function subcategoryCoupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_subcategory', 'subcategory_id', 'coupon_id');
    }

}
