<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallet_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'reason',
        'balance_after',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Transaction types
     */
    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    /**
     * Transaction reasons
     */
    const REASON_DEPOSIT = 'deposit';
    const REASON_REFUND = 'refund';
    const REASON_ORDER_PAYMENT = 'order_payment';
    const REASON_COUPON = 'coupon';
    const REASON_REFERRAL = 'referral';
    const REASON_CASHBACK = 'cashback';
    const REASON_ADJUSTMENT = 'adjustment';

    /**
     * Get the user that owns the wallet transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include credit transactions.
     */
    public function scopeCredits($query)
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    /**
     * Scope a query to only include debit transactions.
     */
    public function scopeDebits($query)
    {
        return $query->where('type', self::TYPE_DEBIT);
    }

    /**
     * Scope a query to only include transactions for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include transactions within a date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get the formatted amount with sign.
     */
    public function getFormattedAmountAttribute(): string
    {
        $sign = $this->type === self::TYPE_CREDIT ? '+' : '-';
        return $sign . '₹' . number_format($this->amount, 2);
    }

    /**
     * Get the formatted balance after transaction.
     */
    public function getFormattedBalanceAfterAttribute(): string
    {
        return '₹' . number_format($this->balance_after, 2);
    }

    /**
     * Get the human-readable reason.
     */
    public function getReasonTextAttribute(): string
    {
        $reasons = [
            self::REASON_DEPOSIT => 'Deposit',
            self::REASON_REFUND => 'Refund',
            self::REASON_ORDER_PAYMENT => 'Order Payment',
            self::REASON_COUPON => 'Coupon',
            self::REASON_REFERRAL => 'Referral Bonus',
            self::REASON_CASHBACK => 'Cashback',
            self::REASON_ADJUSTMENT => 'Adjustment',
        ];

        return $reasons[$this->reason] ?? ucfirst(str_replace('_', ' ', $this->reason));
    }

    /**
     * Create a new wallet transaction.
     */
    public static function createTransaction($userId, $type, $amount, $reason, $balanceAfter)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
            'reason' => $reason,
            'balance_after' => $balanceAfter,
        ]);
    }

    /**
     * Get user's current wallet balance.
     */
    public static function getBalance($userId): float
    {
        $lastTransaction = self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        return $lastTransaction ? (float) $lastTransaction->balance_after : 0;
    }

    /**
     * Get user's transaction history.
     */
    public static function getHistory($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
