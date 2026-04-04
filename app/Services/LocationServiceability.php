<?php

namespace App\Services;

use App\Models\DeliveryLocation;
use App\Models\MasterLocation;
use Illuminate\Support\Collection;

class LocationServiceability
{
    /**
     * Same rules as check-location-in-master: inside a master polygon AND inside at least one vendor delivery polygon.
     *
     * @return array{is_in_master_area: bool, is_in_vendor_area: bool, is_valid_for_selection: bool, vendor_count: int, vendor_ids: array<int>}
     */
    public function analyze(float $lat, float $lng): array
    {
        $insideLocation = MasterLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get()
            ->first(function ($location) use ($lat, $lng) {
                return $this->pointInPolygon($lat, $lng, $location->lat_long);
            });

        $deliveryVendorIds = DeliveryLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->whereNotNull('delivery_lat_long')
            ->get()
            ->filter(function ($location) use ($lat, $lng) {
                return $this->pointInPolygon($lat, $lng, $location->delivery_lat_long);
            })
            ->pluck('vendor_id')
            ->filter()
            ->unique()
            ->values();

        $isDeliverableByVendor = $deliveryVendorIds->isNotEmpty();
        $isValidForSelection = ($insideLocation !== null) && $isDeliverableByVendor;

        return [
            'is_in_master_area' => $insideLocation !== null,
            'is_in_vendor_area' => $isDeliverableByVendor,
            'is_valid_for_selection' => $isValidForSelection,
            'vendor_count' => $deliveryVendorIds->count(),
            'vendor_ids' => $deliveryVendorIds->all(),
        ];
    }

    public function isServiceable(float $lat, float $lng): bool
    {
        return $this->analyze($lat, $lng)['is_valid_for_selection'];
    }

    private function pointInPolygon(float $lat, float $lng, $polygon): bool
    {
        if (is_array($polygon)) {
            $polygon = json_encode($polygon);
        }
        $inside = false;
        $x = $lng;
        $y = $lat;
        $points = collect(json_decode($polygon, true) ?: []);
        $n = $points->count();
        if ($n < 3) {
            return false;
        }

        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $points[$i]['lng'];
            $yi = $points[$i]['lat'];
            $xj = $points[$j]['lng'];
            $yj = $points[$j]['lat'];

            $intersect = (($yi > $y) != ($yj > $y))
                && ($x < ($xj - $xi) * ($y - $yi) / (($yj - $yi) ?: 1e-10) + $xi);
            if ($intersect) {
                $inside = ! $inside;
            }
        }

        return $inside;
    }
}
