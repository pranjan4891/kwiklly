<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VendorAdmin;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_number', 'total_price', 'vendor_id', 'status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function vendor()
    {
        return $this->belongsTo(VendorAdmin::class, 'vendor_id');
    }

}
