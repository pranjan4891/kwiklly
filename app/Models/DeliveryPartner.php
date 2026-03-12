<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryPartner extends Model
{
    use HasFactory;

    protected $table = 'delivery_partners';

    protected $fillable = [
        'name',
        'type',
        'charges_type',
        'charges_value',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'charges_value' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    /** Type: own = in-house delivery boy, others = external */
    const TYPE_OWN = 'own';
    const TYPE_BLUEDART = 'bluedart';
    const TYPE_DUNZO = 'dunzo';
    const TYPE_OTHER = 'other';

    /** charges_type: flat = fixed per order, distance_based = per km, percentage = of order */
    const CHARGES_FLAT = 'flat';
    const CHARGES_DISTANCE_BASED = 'distance_based';
    const CHARGES_PERCENTAGE = 'percentage';

    public function vendorOrders(): HasMany
    {
        return $this->hasMany(VendorOrder::class, 'delivery_partner_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(DeliveryPartnerPayment::class, 'delivery_partner_id');
    }

    /** Rate per km when charges_type is distance_based (for km-wise payment) */
    public function getRatePerKmAttribute(): float
    {
        if ($this->charges_type !== self::CHARGES_DISTANCE_BASED) {
            return 0;
        }
        return (float) $this->charges_value;
    }

    /** Calculate payable amount for given distance (km) */
    public function amountForDistance(float $distanceKm): float
    {
        if ($this->charges_type === self::CHARGES_DISTANCE_BASED) {
            return round($distanceKm * $this->rate_per_km, 2);
        }
        if ($this->charges_type === self::CHARGES_FLAT) {
            return (float) $this->charges_value;
        }
        return 0;
    }
}
