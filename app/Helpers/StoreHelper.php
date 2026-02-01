<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class StoreHelper
{
    /**
     * Output HTML from DB (description, disclaimer, information) with only safe tags allowed.
     * Use in Blade as: {!! \App\Helpers\StoreHelper::safeHtml($product->description) !!}
     *
     * @param string|null $html
     * @param string|null $allowedTags
     * @return string
     */
    public static function safeHtml(?string $html, ?string $allowedTags = null): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }
        $allowed = $allowedTags ?? '<p><br><strong><b><em><i><u><ul><ol><li><a><span><div><h1><h2><h3><h4><h5><h6><table><tr><td><th><tbody><thead><img>';
        return strip_tags($html, $allowed);
    }

    /**
     * Check if vendor store is open right now.
     *
     * Accepts: array, JSON string, Collection or null.
     *
     * @param array|string|\Illuminate\Support\Collection|null $storeTime
     * @return bool
     */
    public static function isStoreOpen($storeTime): bool
    {
        if (empty($storeTime)) {
            return false;
        }

        // If JSON string, decode into array
        if (is_string($storeTime)) {
            $decoded = json_decode($storeTime, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $storeTime = $decoded;
            } else {
                // invalid JSON
                return false;
            }
        }

        // If it's a Collection, convert to array
        if ($storeTime instanceof Collection) {
            $storeTime = $storeTime->toArray();
        }

        if (!is_array($storeTime)) {
            return false;
        }

        $now = Carbon::now();
        $today = $now->format('l'); // e.g. "Monday"

        foreach ($storeTime as $day) {
            if (empty($day['day_name'])) {
                continue;
            }

            if (strtolower($day['day_name']) !== strtolower($today)) {
                continue;
            }

            // status could be "1"/"0" or 1/0
            if (isset($day['status']) && ($day['status'] == '0' || $day['status'] === 0 || $day['status'] === false)) {
                return false;
            }

            if (empty($day['startTime']) || empty($day['endTime'])) {
                return false;
            }

            // parse times (Carbon will assume today's date)
            try {
                $start = Carbon::parse($day['startTime']);
                $end   = Carbon::parse($day['endTime']);
            } catch (\Exception $e) {
                // failed to parse time string
                return false;
            }

            // Normal same-day window
            if ($end->greaterThan($start) || $end->equalTo($start)) {
                if ($now->between($start, $end)) {
                    return true;
                }
            } else {
                // Overnight window (e.g. start 22:00, end 02:00)
                // open if now >= start OR now <= end (end considered next day)
                if ($now->greaterThanOrEqualTo($start) || $now->lessThanOrEqualTo($end)) {
                    return true;
                }
            }
        }

        return false;
    }
}
