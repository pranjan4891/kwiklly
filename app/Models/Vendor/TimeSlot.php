<?php
namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $table = 'time_slots';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['slot_time'];
}
