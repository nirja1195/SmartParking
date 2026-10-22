<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\ParkingSpot;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('now', '+3 days');
        $end = (clone $start)->modify('+'.fake()->numberBetween(2, 6).' hours');

        return [
            'booking_reference' => 'BK-'.strtoupper(Str::random(8)),
            'user_id' => User::factory(),
            'parking_spot_id' => ParkingSpot::factory(),
            'vehicle_id' => Vehicle::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'base_amount' => 60.00,
            'penalty_amount' => 0.00,
            'total_amount' => 60.00,
            'status' => Booking::STATUS_CONFIRMED,
        ];
    }
}
