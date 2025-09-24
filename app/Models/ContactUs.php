<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    protected $table = 'contact_us';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'is_reply',
    ];
    public function replyMessage()
    {
        return $this->hasOne(ContactUsReply::class, 'contact_us_id', 'id');
    }
}
