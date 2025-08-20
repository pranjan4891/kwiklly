<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliverySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'date',
        'time_range',
        'default_minutes',
        'is_express',
        'express_charge',
    ];

    protected $casts = [
        'date' => 'date',
        'is_express' => 'integer', 
        'express_charge' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(VendorAdmin::class, 'vendor_id');
    }
}

