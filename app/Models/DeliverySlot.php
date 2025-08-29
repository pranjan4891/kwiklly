<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliverySlot extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'delivery_slots';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'vendor_id',
        'date',
        'start_time',
        'end_time',
        'is_available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the delivery slot.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vendor that owns the delivery slot.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(VendorAdmin::class, 'vendor_id');
    }

    /**
     * Get the vendor orders for the delivery slot.
     */
    public function vendorOrders(): HasMany
    {
        return $this->hasMany(VendorOrder::class, 'delivery_slot_id');
    }

    /**
     * Scope a query to only include available slots.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include slots for a specific vendor.
     */
    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Scope a query to only include slots for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include future slots.
     */
    public function scopeFuture($query)
    {
        return $query->where('date', '>=', now()->format('Y-m-d'));
    }

    /**
     * Check if the slot is in the past.
     */
    public function isPast(): bool
    {
        return $this->date < now()->format('Y-m-d') ||
               ($this->date == now()->format('Y-m-d') && $this->end_time < now()->format('H:i:s'));
    }

    /**
     * Get the formatted time range.
     */
    public function getTimeRangeAttribute(): string
    {
        return date('g:i A', strtotime($this->start_time)) . ' - ' . date('g:i A', strtotime($this->end_time));
    }

    /**
     * Get the formatted date attribute.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('D, M j, Y');
    }

    /**
     * Mark the slot as unavailable.
     */
    public function markAsUnavailable(): bool
    {
        return $this->update(['is_available' => false]);
    }

    /**
     * Mark the slot as available.
     */
    public function markAsAvailable(): bool
    {
        return $this->update(['is_available' => true]);
    }
}
