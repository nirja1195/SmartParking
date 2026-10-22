<?php

use App\Models\Booking;

if (! function_exists('format_currency')) {
    /**
     * Format numerical amount to standard currency format.
     */
    function format_currency(float|int|string $amount, string $symbol = '$'): string
    {
        return $symbol . number_format((float) $amount, 2);
    }
}

if (! function_exists('booking_status_badge')) {
    /**
     * Get HTML badge class corresponding to booking status.
     */
    function booking_status_badge(string $status): string
    {
        return match ($status) {
            Booking::STATUS_CONFIRMED => 'badge bg-primary text-white',
            Booking::STATUS_ACTIVE => 'badge bg-success text-white',
            Booking::STATUS_COMPLETED => 'badge bg-secondary text-white',
            Booking::STATUS_CANCELLED => 'badge bg-danger text-white',
            Booking::STATUS_OVERDUE => 'badge bg-warning text-dark',
            default => 'badge bg-light text-dark',
        };
    }
}

if (! function_exists('spot_type_badge')) {
    /**
     * Get human-readable label for parking spot types.
     */
    function spot_type_badge(string $type): string
    {
        return match ($type) {
            'ev' => 'EV Charging',
            'compact' => 'Compact / 2-Wheeler',
            'handicapped' => 'Accessible',
            'truck' => 'Truck / Large',
            default => 'Standard',
        };
    }
}

if (! function_exists('setting')) {
    /**
     * Get system setting value from database.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}
