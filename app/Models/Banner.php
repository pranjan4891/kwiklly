<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = [
        'banner_cat_id',
        'desktop_image',
        'mobile_image',
        'banner_url',
        'master_category_id',
        'position',
        'is_active',
        'is_deleted',

    ];

}
