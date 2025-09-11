<?php 
namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    protected $table = 'vendor_categories';
    protected $fillable = [
        'vendor_id',
        'category_name',
        'category_image',
    ];
}
