<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'city_id',
        'pincode',
        'place',
        'lat_long',
        'is_active',
        'is_deleted',
    ];

    protected $casts = [
        'lat_long' => 'json',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    // Optional: relationships
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
