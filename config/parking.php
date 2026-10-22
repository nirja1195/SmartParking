<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Pricing & Rates
    |--------------------------------------------------------------------------
    |
    | Base pricing configurations used across booking services when lot-specific
    | overrides are not defined.
    |
    */
    'default_hourly_rate' => (float) env('PARKING_DEFAULT_HOURLY_RATE', 20.00),
    'overdue_penalty_rate_per_hour' => (float) env('PARKING_OVERDUE_PENALTY_RATE', 15.00),

    /*
    |--------------------------------------------------------------------------
    | Reservation Rules & Time Constraints
    |--------------------------------------------------------------------------
    */
    'max_reservation_hours' => (int) env('PARKING_MAX_RESERVATION_HOURS', 72),
    'min_reservation_hours' => 1,
    'slot_buffer_minutes' => (int) env('PARKING_SLOT_BUFFER_MINUTES', 15),

    /*
    |--------------------------------------------------------------------------
    | Supported Spot Types
    |--------------------------------------------------------------------------
    */
    'spot_types' => [
        'compact' => [
            'label' => 'Compact / Two-Wheeler',
            'multiplier' => 0.8,
        ],
        'standard' => [
            'label' => 'Standard Sedan / SUV',
            'multiplier' => 1.0,
        ],
        'ev' => [
            'label' => 'Electric Vehicle (with Charging)',
            'multiplier' => 1.3,
        ],
        'handicapped' => [
            'label' => 'Accessible / Reserved',
            'multiplier' => 1.0,
        ],
        'truck' => [
            'label' => 'Heavy / Commercial Truck',
            'multiplier' => 1.8,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Booking Status Lifecycle
    |--------------------------------------------------------------------------
    */
    'statuses' => [
        'pending' => 'Pending Confirmation',
        'confirmed' => 'Confirmed',
        'active' => 'Active / Checked In',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'overdue' => 'Overdue',
    ],

];
