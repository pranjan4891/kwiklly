<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingCheckout extends Model
{
    protected $table = 'pending_checkouts';

    protected $fillable = [
        'user_id',
        'cust_address_id',
        'amount',
        'currency',
        'order_data',
        'status',
    ];

    protected $casts = [
        'order_data' => 'array',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'cust_address_id');
    }
}
