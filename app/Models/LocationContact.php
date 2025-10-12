<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'contact_method',
        'contact_detail'
    ];

    protected $casts = [
        'contact_method' => 'string',
    ];

    public function vendor()
    {
        return $this->belongsTo(VendorAdmin::class, 'vendor_id');
    }
}
