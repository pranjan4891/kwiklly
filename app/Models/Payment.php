<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'pending_checkout_id', 'user_id', 'payment_method', 'payment_status',
        'transaction_id', 'amount', 'currency', 'gateway_response', 'gateway_reference',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pendingCheckout()
    {
        return $this->belongsTo(PendingCheckout::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
