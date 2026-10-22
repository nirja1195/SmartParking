<?php

namespace Database\Factories;

use App\Models\ParkingLot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParkingLot>
 */
class ParkingLotFactory extends Factory
{
    protected $model = ParkingLot::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company().' Parking Plaza',
            'code' => 'LOT-'.strtoupper(Str::random(4)),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'latitude' => fake()->latitude(18.0, 28.0),
            'longitude' => fake()->longitude(72.0, 88.0),
            'total_spots' => 50,
            'hourly_rate' => fake()->randomElement([20.00, 30.00, 40.00, 50.00]),
            'is_active' => true,
        ];
    }
}
