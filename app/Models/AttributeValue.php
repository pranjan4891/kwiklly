<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;
    protected $fillable = [
        'attribute_id',
        'value',
        'color_code',
        'is_active',
        'is_deleted',
    ];

    /** Whether this value has an image (e.g. color swatch). */
    public function hasImage(): bool
    {
        return !empty($this->image);
    }
    protected $table = 'attribute_values';

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

}
