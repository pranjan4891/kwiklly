<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_active',
        'is_deleted',
    ];
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
