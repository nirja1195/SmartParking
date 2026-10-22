<?php

namespace Database\Seeders;

use App\Models\ParkingLot;
use App\Models\ParkingSpot;
use Illuminate\Database\Seeder;

class ParkingLotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lotsData = [
            [
                'name' => 'Metro Central Grand Plaza',
                'code' => 'MC-GP01',
                'address' => '100 Financial Boulevard, Downtown',
                'city' => 'Metropolis',
                'latitude' => 40.712776,
                'longitude' => -74.005974,
                'total_spots' => 30,
                'hourly_rate' => 25.00,
                'is_active' => true,
                'floors' => ['G', '1', '2'],
            ],
            [
                'name' => 'Airport Express Terminal Hub',
                'code' => 'AP-TH02',
                'address' => 'Terminal 3 Access Road, Aviation Way',
                'city' => 'Metropolis',
                'latitude' => 40.641311,
                'longitude' => -73.778139,
                'total_spots' => 50,
                'hourly_rate' => 35.00,
                'is_active' => true,
                'floors' => ['Basement 1', 'Ground', 'Level 1'],
            ],
        ];

        foreach ($lotsData as $data) {
            $floors = $data['floors'];
            unset($data['floors']);

            $lot = ParkingLot::updateOrCreate(['code' => $data['code']], $data);

            // Generate structured parking spots
            $spotTypes = ['standard', 'standard', 'standard', 'compact', 'ev', 'handicapped'];
            $spotIndex = 1;

            foreach ($floors as $floor) {
                for ($i = 1; $i <= 10; $i++) {
                    $type = $spotTypes[($spotIndex - 1) % count($spotTypes)];
                    $spotNumber = sprintf('%s-%02d', $floor[0], $i);

                    ParkingSpot::updateOrCreate(
                        [
                            'parking_lot_id' => $lot->id,
                            'spot_number' => $spotNumber,
                        ],
                        [
                            'floor' => $floor,
                            'spot_type' => $type,
                            'is_active' => true,
                            'is_occupied' => false,
                            'hourly_rate_override' => null,
                        ]
                    );

                    $spotIndex++;
                }
            }
        }
    }
}
