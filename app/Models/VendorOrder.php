<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'vendor_id',
        'coupon_id',
        'coupon_discount',
        'delivery_slot_id',
        'sub_total',
        'delivery_fee',
        'final_amount',
        'delivery_status',
        'delivery_partner_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(VendorAdmin::class);
    }
}
