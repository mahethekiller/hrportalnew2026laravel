<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use DateTimeInterface;
use Throwable;

class DateHelper
{
    /**
     * Check if a given date value is considered empty or invalid.
     */
    public static function isEmpty(mixed $date): bool
    {
        if ($date === null) {
            return true;
        }

        $str = trim((string) $date);
        return $str === '' 
            || $str === '0000-00-00' 
            || $str === '0000-00-00 00:00:00' 
            || $str === '00:00:00' 
            || strtolower($str) === 'null' 
            || strtolower($str) === 'n/a';
    }

    /**
     * Parse date safely to Carbon instance.
     */
    public static function parse(mixed $date): ?Carbon
    {
        if (self::isEmpty($date)) {
            return null;
        }

        if ($date instanceof Carbon) {
            return $date;
        }

        if ($date instanceof DateTimeInterface) {
            return Carbon::instance($date);
        }

        try {
            return Carbon::parse($date);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Format a date into human-readable format: "Sep 21, 2026"
     */
    public static function format(mixed $date, string $format = 'M d, Y', string $fallback = '--'): string
    {
        $carbon = self::parse($date);
        if (!$carbon) {
            return $fallback;
        }

        return $carbon->format($format);
    }

    /**
     * Format a timestamp into human-readable date & time: "Sep 21, 2026 • 10:24 AM"
     */
    public static function formatDateTime(mixed $date, string $format = 'M d, Y • h:i A', string $fallback = '--'): string
    {
        $carbon = self::parse($date);
        if (!$carbon) {
            return $fallback;
        }

        return $carbon->format($format);
    }

    /**
     * Relative difference for humans: "2 days ago", "in 3 days"
     */
    public static function diffForHumans(mixed $date, string $fallback = ''): string
    {
        $carbon = self::parse($date);
        if (!$carbon) {
            return $fallback;
        }

        return $carbon->diffForHumans();
    }

    /**
     * Generate hover tooltip text containing relative time and ISO date.
     */
    public static function tooltip(mixed $date): string
    {
        $carbon = self::parse($date);
        if (!$carbon) {
            return '';
        }

        return $carbon->diffForHumans() . ' (' . $carbon->format('Y-m-d H:i:s') . ')';
    }
}
