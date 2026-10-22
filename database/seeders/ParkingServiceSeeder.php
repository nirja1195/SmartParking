<?php

namespace Database\Seeders;

use App\Models\ParkingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ParkingServiceSeeder extends Seeder
{
    /**
     * Seed default dynamic parking services matching the reference design.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Car Parking',
                'slug' => 'car-parking',
                'description' => 'Safe and secure parking for your car.',
                'short_description' => 'Safe and secure parking for your car.',
                'image' => 'images/services/card-car.png',
                'icon' => 'bi bi-car-front-fill',
                'button_text' => 'Book Now',
                'button_url' => '',
                'status' => 'Active',
                'sort_order' => 1,
            ],
            [
                'name' => 'Bike Parking',
                'slug' => 'bike-parking',
                'description' => 'Convenient parking for two-wheelers.',
                'short_description' => 'Convenient parking for two-wheelers.',
                'image' => 'images/services/card-bike.png',
                'icon' => 'bi bi-bicycle',
                'button_text' => 'Book Now',
                'button_url' => '',
                'status' => 'Active',
                'sort_order' => 2,
            ],
            [
                'name' => 'EV Charging',
                'slug' => 'ev-charging',
                'description' => 'Park and charge your electric vehicle.',
                'short_description' => 'Park and charge your electric vehicle.',
                'image' => 'images/services/card-ev.png',
                'icon' => 'bi bi-ev-station-fill',
                'button_text' => 'Book Now',
                'button_url' => '',
                'status' => 'Active',
                'sort_order' => 3,
            ],
            [
                'name' => 'Accessible Parking',
                'slug' => 'accessible-parking',
                'description' => 'Dedicated spaces for differently-abled.',
                'short_description' => 'Dedicated spaces for differently-abled.',
                'image' => 'images/services/card-accessible.png',
                'icon' => 'bi bi-person-wheelchair',
                'button_text' => 'Book Now',
                'button_url' => '',
                'status' => 'Active',
                'sort_order' => 4,
            ],
            [
                'name' => 'Hourly / Daily Parking',
                'slug' => 'hourly-daily-parking',
                'description' => 'Flexible plans for short or long stays.',
                'short_description' => 'Flexible plans for short or long stays.',
                'image' => 'images/services/card-hourly.png',
                'icon' => 'bi bi-clock-fill',
                'button_text' => 'Book Now',
                'button_url' => '',
                'status' => 'Active',
                'sort_order' => 5,
            ],
            [
                'name' => 'Premium Parking',
                'slug' => 'premium-parking',
                'description' => 'Spacious and secure premium parking spaces.',
                'short_description' => 'Spacious and secure premium parking spaces.',
                'image' => 'images/services/card-premium.png',
                'icon' => 'bi bi-shield-fill-check',
                'button_text' => 'Book Now',
                'button_url' => '',
                'status' => 'Active',
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            ParkingService::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
