<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryPartnerPayment extends Model
{
    use HasFactory;

    protected $table = 'delivery_partner_payments';

    protected $fillable = [
        'delivery_partner_id',
        'vendor_order_id',
        'distance_km',
        'rate_per_km',
        'amount',
        'status',
        'paid_at',
        'payment_reference',
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'rate_per_km' => 'decimal:2',
        'amount'      => 'decimal:2',
        'paid_at'     => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';

    public function deliveryPartner(): BelongsTo
    {
        return $this->belongsTo(DeliveryPartner::class);
    }

    public function vendorOrder(): BelongsTo
    {
        return $this->belongsTo(VendorOrder::class);
    }

    public function markAsPaid(?string $reference = null): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'payment_reference' => $reference ?? $this->payment_reference,
        ]);
    }

    /**
     * Assign delivery boy to order with distance (km) and create km-wise payment record.
     * Use when vendor/admin assigns a delivery partner and enters delivery distance.
     */
    public static function createForOrder(VendorOrder $vendorOrder, DeliveryPartner $partner, float $distanceKm): self
    {
        $ratePerKm = $partner->charges_type === DeliveryPartner::CHARGES_DISTANCE_BASED
            ? (float) $partner->charges_value
            : 0;
        $amount = $partner->amountForDistance($distanceKm);

        $vendorOrder->update([
            'delivery_partner_id' => $partner->id,
            'delivery_distance_km' => $distanceKm,
        ]);

        return self::updateOrCreate(
            ['vendor_order_id' => $vendorOrder->id],
            [
                'delivery_partner_id' => $partner->id,
                'distance_km' => $distanceKm,
                'rate_per_km' => $ratePerKm,
                'amount' => $amount,
                'status' => self::STATUS_PENDING,
            ]
        );
    }
}
