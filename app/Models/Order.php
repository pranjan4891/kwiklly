<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cust_address_id',
        'order_number',
        'total_price',
        'coupon_id',
        'coupon_discount',
        'wallet_used',
        'final_amount',
        'status',
    ];

    public function items()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            VendorOrder::class,
            'order_id', // Foreign key on VendorOrder table
            'vendor_order_id', // Foreign key on OrderItem table
            'id', // Local key on Order table
            'id' // Local key on VendorOrder table
        );
    }

    public function vendorOrders(): HasMany
    {
        return $this->hasMany(VendorOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class, 'cust_address_id');
    }
}
