<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'customer_addresses';

    protected $fillable = [
        'user_id',
        'type',
        'area',
        'flat',
        'landmark',
        'pincode',
        'latitude',
        'longitude',
        'name',
        'phone',
        'alt_phone',
        'full_address',
        'is_selected',
    ];

    protected $casts = [
        'latitude'  => 'decimal:6',
        'longitude' => 'decimal:6',
        'is_selected' => 'boolean',
    ];

    /**
     * Relation: address belongs to user (customer).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this address (lat/long) falls inside the given vendor/branch's delivery area.
     * Vendor and branch both use delivery_locations with vendor_id = their id.
     */
    public function isDeliverableByVendor(int $vendorId): bool
    {
        if (!$this->latitude || !$this->longitude) {
            return false;
        }
        $lat = (float) $this->latitude;
        $lng = (float) $this->longitude;

        $locations = DeliveryLocation::where('vendor_id', $vendorId)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();

        foreach ($locations as $loc) {
            $polygon = $loc->delivery_lat_long;
            if (is_string($polygon)) {
                $polygon = json_decode($polygon, true);
            }
            if ($polygon && $this->pointInPolygon($lat, $lng, $polygon)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if this address can be delivered by the given VendorAdmin (vendor or branch).
     */
    public function isDeliverableBy(VendorAdmin $vendorOrBranch): bool
    {
        return $this->isDeliverableByVendor($vendorOrBranch->id);
    }

    /**
     * Point-in-polygon check (ray casting).
     */
    protected static function pointInPolygon(float $lat, float $lng, array $polygon): bool
    {
        $inside = false;
        $x = $lng;
        $y = $lat;
        $n = count($polygon);
        $j = $n - 1;

        for ($i = 0; $i < $n; $j = $i++) {
            $xi = $polygon[$i]['lng'] ?? $polygon[$i][1] ?? 0;
            $yi = $polygon[$i]['lat'] ?? $polygon[$i][0] ?? 0;
            $xj = $polygon[$j]['lng'] ?? $polygon[$j][1] ?? 0;
            $yj = $polygon[$j]['lat'] ?? $polygon[$j][0] ?? 0;

            $intersect = (($yi > $y) != ($yj > $y))
                && ($x < ($xj - $xi) * ($y - $yi) / (($yj - $yi) ?: 1e-10) + $xi);
            if ($intersect) {
                $inside = !$inside;
            }
        }
        return $inside;
    }

    /**
     * Haversine distance in km between two lat/lng points.
     */
    public static function distanceInKm(?float $lat1, ?float $lng1, ?float $lat2, ?float $lng2): ?float
    {
        if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null) {
            return null;
        }
        $R = 6371; // Earth radius in km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }
}
