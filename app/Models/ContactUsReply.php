<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUsReply extends Model
{
    use HasFactory;
    protected $table = 'contact_us_replies';

    protected $fillable = [
        'contact_us_id',
        'reply_message',
    ];
}
