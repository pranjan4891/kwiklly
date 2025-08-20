<?php 
namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class VendorSubCategory extends Model
{
    protected $table = 'vendor_subcategories';
    protected $fillable = [
        'vendor_id',
        'category_id',
        'subcategory_name',
    ];
    public function category()
    {
        return $this->belongsTo(VendorCategory::class, 'cat_id', 'id');
    }
}
