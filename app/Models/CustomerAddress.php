<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customer_addresses';
    protected $fillable = [
        'user_id',
        'type',
        'area',
        'flat',
        'landmark',
        'pincode',
        'name',
        'phone',
        'alt_phone',
        'full_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
