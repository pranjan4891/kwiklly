<?php 
namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'vendor_id',
        'name',
        'slug',
        'category_id',
        'subcategory_id',
        'actual_price',
        'selling_price',
        'save_price_in_rs',
        'save_price_in_percent',
        'description',
        'disclaimer',
        'information',
        'cgst',
        'sgst',
        'image',
    ];
   
}
