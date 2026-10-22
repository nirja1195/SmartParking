<?php

namespace Database\Seeders;

use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use Illuminate\Database\Seeder;

class ParkingLocationsSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'City Center Mall',
                'location_type' => 'Commercial',
                'address' => 'CG Road, Ahmedabad',
                'area' => 'CG Road',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380009',
                'contact_number' => '+91 79 2640 0001',
                'opening_time' => '08:00',
                'closing_time' => '23:00',
                'parking_charges' => 30.00,
                'status' => 'Active',
                'image' => 'images/locations/city-center.jpg',
                'total_slots' => 200,
                'available' => 120,
                'occupied' => 80,
            ],
            [
                'name' => 'Riverview Mall',
                'location_type' => 'Commercial',
                'address' => 'Iscon, Ahmedabad',
                'area' => 'Iscon',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380015',
                'contact_number' => '+91 79 2640 0002',
                'opening_time' => '09:00',
                'closing_time' => '23:30',
                'parking_charges' => 25.00,
                'status' => 'Active',
                'image' => 'images/locations/riverview-mall.jpg',
                'total_slots' => 150,
                'available' => 45,
                'occupied' => 105,
            ],
            [
                'name' => 'Airport Parking',
                'location_type' => 'Airport',
                'address' => 'Sardar Vallabhbhai Patel Intl.',
                'area' => 'Sardar Vallabhbhai Patel Intl.',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380003',
                'contact_number' => '+91 79 2640 0003',
                'opening_time' => '00:00',
                'closing_time' => '23:59',
                'parking_charges' => 50.00,
                'status' => 'Active',
                'image' => 'images/locations/airport.jpg',
                'total_slots' => 300,
                'available' => 180,
                'occupied' => 120,
            ],
            [
                'name' => 'Business Park',
                'location_type' => 'Office',
                'address' => 'Prahladnagar, Ahmedabad',
                'area' => 'Prahladnagar',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380015',
                'contact_number' => '+91 79 2640 0004',
                'opening_time' => '07:30',
                'closing_time' => '22:00',
                'parking_charges' => 20.00,
                'status' => 'Active',
                'image' => 'images/locations/business-park.jpg',
                'total_slots' => 120,
                'available' => 60,
                'occupied' => 60,
            ],
            [
                'name' => 'Metro Station',
                'location_type' => 'Transit',
                'address' => 'Vastrapur, Ahmedabad',
                'area' => 'Vastrapur',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380054',
                'contact_number' => '+91 79 2640 0005',
                'opening_time' => '06:00',
                'closing_time' => '23:00',
                'parking_charges' => 15.00,
                'status' => 'Active',
                'image' => 'images/locations/metro-station.jpg',
                'total_slots' => 100,
                'available' => 20,
                'occupied' => 80,
            ],
            [
                'name' => 'City Care Hospital',
                'location_type' => 'Hospital',
                'address' => 'Shahibaug, Ahmedabad',
                'area' => 'Shahibaug',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380004',
                'contact_number' => '+91 79 2640 0006',
                'opening_time' => '00:00',
                'closing_time' => '23:59',
                'parking_charges' => 20.00,
                'status' => 'Active',
                'image' => 'images/locations/hospital.jpg',
                'total_slots' => 180,
                'available' => 90,
                'occupied' => 90,
            ],
            [
                'name' => 'University Area',
                'location_type' => 'Educational',
                'address' => 'Navrangpura, Ahmedabad',
                'area' => 'Navrangpura',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380009',
                'contact_number' => '+91 79 2640 0007',
                'opening_time' => '07:00',
                'closing_time' => '21:00',
                'parking_charges' => 15.00,
                'status' => 'Inactive',
                'image' => 'images/locations/university.jpg',
                'total_slots' => 100,
                'available' => 0,
                'occupied' => 100,
            ],
            [
                'name' => 'Law Garden',
                'location_type' => 'Public',
                'address' => 'Ellisbridge, Ahmedabad',
                'area' => 'Ellisbridge',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380006',
                'contact_number' => '+91 79 2640 0008',
                'opening_time' => '06:00',
                'closing_time' => '22:00',
                'parking_charges' => 10.00,
                'status' => 'Inactive',
                'image' => 'images/locations/law-garden.jpg',
                'total_slots' => 90,
                'available' => 5,
                'occupied' => 85,
            ],
        ];

        foreach ($locations as $data) {
            $totalSlots = $data['total_slots'];
            $availCount = $data['available'];
            $occCount = $data['occupied'];

            unset($data['total_slots'], $data['available'], $data['occupied']);

            $location = ParkingLocation::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['total_capacity' => $totalSlots])
            );

            // Ensure Zone exists
            $zone = ParkingZone::updateOrCreate(
                [
                    'parking_location_id' => $location->id,
                    'zone_name' => 'Main Zone',
                ],
                [
                    'vehicle_type' => 'Car',
                    'floor' => 'Ground',
                    'section' => 'A',
                    'total_slots' => $totalSlots,
                    'status' => 'Active',
                ]
            );

            // Create slots if count doesn't match
            $existingSlotsCount = $location->slots()->count();
            if ($existingSlotsCount < $totalSlots) {
                // Wipe old slots for clean setup
                $location->slots()->delete();

                $slotsToCreate = [];
                for ($i = 1; $i <= $totalSlots; $i++) {
                    $status = ParkingSlot::STATUS_AVAILABLE;
                    if ($i > $availCount) {
                        $status = ParkingSlot::STATUS_OCCUPIED;
                    }

                    $slotsToCreate[] = [
                        'parking_location_id' => $location->id,
                        'zone_id' => $zone->id,
                        'slot_number' => sprintf('A-%03d', $i),
                        'vehicle_type' => 'Car',
                        'floor' => 'Ground',
                        'section' => 'A',
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (count($slotsToCreate) >= 100) {
                        ParkingSlot::insert($slotsToCreate);
                        $slotsToCreate = [];
                    }
                }

                if (!empty($slotsToCreate)) {
                    ParkingSlot::insert($slotsToCreate);
                }
            }
        }
    }
}
