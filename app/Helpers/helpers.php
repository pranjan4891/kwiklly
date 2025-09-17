<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;

if (! function_exists('isStoreOpen')) {
    /**
     * Check if vendor store is open right now.
     *
     * Accepts: array, JSON string, Collection or null.
     */
    function isStoreOpen($storeTime): bool
    {
        if (empty($storeTime)) {
            return false;
        }

        if (is_string($storeTime)) {
            $decoded = json_decode($storeTime, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $storeTime = $decoded;
            } else {
                return false;
            }
        }

        if ($storeTime instanceof Collection) {
            $storeTime = $storeTime->toArray();
        }

        if (!is_array($storeTime)) {
            return false;
        }

        $now = Carbon::now();
        $today = $now->format('l');

        foreach ($storeTime as $day) {
            if (empty($day['day_name'])) continue;
            if (strtolower($day['day_name']) !== strtolower($today)) continue;
            if (isset($day['status']) && ($day['status'] == '0' || $day['status'] === 0 || $day['status'] === false)) {
                return false;
            }
            if (empty($day['startTime']) || empty($day['endTime'])) return false;

            try {
                $start = Carbon::parse($day['startTime']);
                $end   = Carbon::parse($day['endTime']);
            } catch (\Exception $e) {
                return false;
            }

            if ($end->greaterThan($start) || $end->equalTo($start)) {
                if ($now->between($start, $end)) return true;
            } else {
                if ($now->greaterThanOrEqualTo($start) || $now->lessThanOrEqualTo($end)) return true;
            }
        }

        return false;
    }
}
