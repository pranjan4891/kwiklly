<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryLocation extends Model
{
    use HasFactory;
    protected $table = 'delivery_locations';
    protected $fillable = [
        'vendor_id',
        'delivery_lat_long',
        'is_active',
        'is_deleted'
    ];
    protected $casts = [
        'delivery_lat_long' => 'array', // Cast to array for JSON storage
    ];
    protected $attributes = [
        'is_active' => true, // Default to active
        'is_deleted' => false, // Default to not deleted
    ];
    public function vendor()
    {
        return $this->belongsTo(VendorAdmin::class, 'vendor_id');
    }
}
