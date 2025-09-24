<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    use HasFactory;
    protected $table = 'delivery_charges';
    protected $fillable = [
        'vendor_id',
        'delivery_charge',
        'delivery_range',
        'status',

    ];

    public function vendor()
    {
        return $this->belongsTo(VendorAdmin::class);
    }


}
