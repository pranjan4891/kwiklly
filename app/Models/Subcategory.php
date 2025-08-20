<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $table = 'subcategories';
    protected $fillable = [
        'va_id',
        'category_id',
        'attribute_id',
        'sub_cat_name',
        'sub_cat_slug',
        'image',
        'is_active',
        'is_deleted',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_subcategory');
    }
}
