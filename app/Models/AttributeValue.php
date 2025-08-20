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
        'is_active',
        'is_deleted',
    ];
    protected $table = 'attribute_values';

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

}
