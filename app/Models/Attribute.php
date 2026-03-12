<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'is_active',
        'is_deleted',
    ];

    /** Attribute types: text = size, memory, quantity (no image). color = value has image (swatch/thumbnail). */
    const TYPE_TEXT = 'text';
    const TYPE_COLOR = 'color';
    protected $table = 'attributes';
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_subcategory');
    }

}
