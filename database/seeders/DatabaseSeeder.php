<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\ParkingEntry;
use App\Models\ParkingExit;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Models\Payment;
use App\Models\Staff;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Requirement #55: Comprehensive Seeder with Admin, Staff, Users, Locations, Zones, Slots.
     */
    public function run(): void
    {
        // 1. Create 1 Admin
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@smartpark.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'Super Admin',
                'status' => 'active',
            ]
        );

        // 2. Create 5 Users
        $usersData = [
            ['name' => 'Alex Johnson', 'email' => 'user@smartpark.com', 'phone' => '9876543210', 'address' => '42 Park Avenue, Metropolis'],
            ['name' => 'Samantha Smith', 'email' => 'samantha@smartpark.com', 'phone' => '9876543211', 'address' => '15 Maple Street, Metropolis'],
            ['name' => 'Michael Brown', 'email' => 'michael@smartpark.com', 'phone' => '9876543212', 'address' => '78 Oak Road, Downtown'],
            ['name' => 'Emily Davis', 'email' => 'emily@smartpark.com', 'phone' => '9876543213', 'address' => '91 Pine Lane, North City'],
            ['name' => 'Daniel Wilson', 'email' => 'daniel@smartpark.com', 'phone' => '9876543214', 'address' => '104 Cedar Boulevard, Eastside'],
        ];

        $users = [];
        foreach ($usersData as $u) {
            $users[] = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'phone' => $u['phone'],
                    'address' => $u['address'],
                    'password' => Hash::make('password'),
                    'status' => 'active',
                ]
            );
        }

        // 3. Create Vehicles for users
        $vehiclesData = [
            ['user_id' => $users[0]->id, 'vehicle_number' => 'MH-02-AB-1234', 'vehicle_type' => 'Car', 'vehicle_model' => 'Honda City', 'vehicle_color' => 'White', 'is_default' => true],
            ['user_id' => $users[0]->id, 'vehicle_number' => 'MH-02-XY-5678', 'vehicle_type' => 'Bike', 'vehicle_model' => 'Yamaha R15', 'vehicle_color' => 'Blue', 'is_default' => false],
            ['user_id' => $users[1]->id, 'vehicle_number' => 'DL-01-CD-4321', 'vehicle_type' => 'EV', 'vehicle_model' => 'Tata Nexon EV', 'vehicle_color' => 'Teal', 'is_default' => true],
            ['user_id' => $users[2]->id, 'vehicle_number' => 'KA-05-EF-9876', 'vehicle_type' => 'Car', 'vehicle_model' => 'Hyundai Creta', 'vehicle_color' => 'Black', 'is_default' => true],
            ['user_id' => $users[3]->id, 'vehicle_number' => 'MH-12-GH-3456', 'vehicle_type' => 'Auto', 'vehicle_model' => 'Bajaj Compact', 'vehicle_color' => 'Yellow', 'is_default' => true],
            ['user_id' => $users[4]->id, 'vehicle_number' => 'KA-01-IJ-7890', 'vehicle_type' => 'Car', 'vehicle_model' => 'Toyota Fortuner', 'vehicle_color' => 'Silver', 'is_default' => true],
        ];

        $vehicles = [];
        foreach ($vehiclesData as $v) {
            $vehicles[] = Vehicle::updateOrCreate(['vehicle_number' => $v['vehicle_number']], $v);
        }

        // 4. Create 5 Parking Locations
        $locationsData = [
            [
                'name' => 'City Center Mall',
                'address' => 'MG Road, Commercial District',
                'city' => 'Metropolis',
                'state' => 'Central',
                'pincode' => '400001',
                'contact_number' => '+91 22 5555 0101',
                'opening_time' => '08:00',
                'closing_time' => '23:30',
                'parking_charges' => 30.00,
                'description' => 'Multi-level premium parking with EV fast chargers and 24/7 security surveillance.',
                'facilities' => 'EV Charging, Covered Parking, CCTV, Elevator, Restroom',
                'status' => 'Active',
            ],
            [
                'name' => 'Metro Mall',
                'address' => 'Station Road, Sector 4',
                'city' => 'Metropolis',
                'state' => 'Central',
                'pincode' => '400012',
                'contact_number' => '+91 22 5555 0102',
                'opening_time' => '07:00',
                'closing_time' => '23:00',
                'parking_charges' => 25.00,
                'description' => 'Spacious underground parking directly connected to the metro transit station.',
                'facilities' => 'Direct Metro Access, Covered Parking, Security Guard',
                'status' => 'Active',
            ],
            [
                'name' => 'Riverfront Parking',
                'address' => 'Promenade Walkway, Promenade West',
                'city' => 'Metropolis',
                'state' => 'Central',
                'pincode' => '400020',
                'contact_number' => '+91 22 5555 0103',
                'opening_time' => '06:00',
                'closing_time' => '22:00',
                'parking_charges' => 20.00,
                'description' => 'Scenic open-air parking facility adjacent to the riverfront recreational zone.',
                'facilities' => 'Open Air, Security Guard, Valet Service',
                'status' => 'Active',
            ],
            [
                'name' => 'Airport Parking',
                'address' => 'Terminal 2 Approach, Aviation Express',
                'city' => 'Metropolis',
                'state' => 'Central',
                'pincode' => '400099',
                'contact_number' => '+91 22 5555 0104',
                'opening_time' => '00:00',
                'closing_time' => '23:59',
                'parking_charges' => 50.00,
                'description' => '24/7 high-capacity long-term and short-term airport departure terminal parking.',
                'facilities' => '24/7 Open, EV Charging, Luggage Assistance, Covered Parking',
                'status' => 'Active',
            ],
            [
                'name' => 'University Parking',
                'address' => 'Campus Boulevard, Academic Gate 3',
                'city' => 'Metropolis',
                'state' => 'Central',
                'pincode' => '400045',
                'contact_number' => '+91 22 5555 0105',
                'opening_time' => '07:00',
                'closing_time' => '21:00',
                'parking_charges' => 15.00,
                'description' => 'Affordable shaded parking for students, faculty, and campus visitors.',
                'facilities' => 'Shaded Parking, Bike Racks, Security Guard',
                'status' => 'Active',
            ],
        ];

        $locations = [];
        foreach ($locationsData as $loc) {
            $locations[] = ParkingLocation::updateOrCreate(['name' => $loc['name']], $loc);
        }

        // 5. Create 2 Staff accounts
        $staffMembers = [
            [
                'parking_location_id' => $locations[0]->id,
                'name' => 'John Gatekeeper',
                'email' => 'staff@smartpark.com',
                'phone' => '9123456780',
                'password' => Hash::make('password'),
                'role' => 'Entry Staff',
                'status' => 'active',
            ],
            [
                'parking_location_id' => $locations[0]->id,
                'name' => 'Robert Gatekeeper',
                'email' => 'exitstaff@smartpark.com',
                'phone' => '9123456781',
                'password' => Hash::make('password'),
                'role' => 'Exit Staff',
                'status' => 'active',
            ],
        ];

        foreach ($staffMembers as $s) {
            Staff::updateOrCreate(['email' => $s['email']], $s);
        }

        // 6. Create Zones and Slots for each Location
        foreach ($locations as $locIndex => $loc) {
            $zonesInfo = [
                ['name' => 'Zone A', 'type' => 'Car', 'floor' => 'Ground', 'section' => 'North', 'prefix' => 'A', 'count' => 10],
                ['name' => 'Zone B', 'type' => 'Car', 'floor' => 'Level 1', 'section' => 'South', 'prefix' => 'B', 'count' => 8],
                ['name' => 'Zone EV', 'type' => 'EV', 'floor' => 'Ground', 'section' => 'East', 'prefix' => 'EV', 'count' => 5],
            ];

            $totalLocCapacity = 0;

            foreach ($zonesInfo as $z) {
                $zone = ParkingZone::updateOrCreate(
                    [
                        'parking_location_id' => $loc->id,
                        'zone_name' => $z['name'],
                    ],
                    [
                        'vehicle_type' => $z['type'],
                        'floor' => $z['floor'],
                        'section' => $z['section'],
                        'total_slots' => $z['count'],
                        'status' => 'Active',
                    ]
                );

                for ($i = 1; $i <= $z['count']; $i++) {
                    $slotNum = sprintf('%s%02d', $z['prefix'], $i);
                    // Leave most available, with a few occupied/reserved for realistic demo
                    $status = ParkingSlot::STATUS_AVAILABLE;

                    if ($locIndex === 0 && $z['name'] === 'Zone A') {
                        if ($i === 3) $status = ParkingSlot::STATUS_RESERVED;
                        if ($i === 4) $status = ParkingSlot::STATUS_OCCUPIED;
                    }

                    ParkingSlot::updateOrCreate(
                        [
                            'zone_id' => $zone->id,
                            'slot_number' => $slotNum,
                        ],
                        [
                            'parking_location_id' => $loc->id,
                            'vehicle_type' => $z['type'],
                            'floor' => $z['floor'],
                            'section' => $z['section'],
                            'status' => $status,
                        ]
                    );

                    $totalLocCapacity++;
                }
            }

            $loc->update(['total_capacity' => $totalLocCapacity]);
        }

        // 7. Create Demo Bookings
        $cityMall = $locations[0];
        $zoneA = ParkingZone::where('parking_location_id', $cityMall->id)->where('zone_name', 'Zone A')->first();
        $slotA3 = ParkingSlot::where('zone_id', $zoneA->id)->where('slot_number', 'A03')->first();
        $slotA4 = ParkingSlot::where('zone_id', $zoneA->id)->where('slot_number', 'A04')->first();

        // 7a. Reserved Booking (for A03)
        $bookingReserved = Booking::updateOrCreate(
            ['booking_id' => 'SP202609210001'],
            [
                'user_id' => $users[0]->id,
                'vehicle_id' => $vehicles[0]->id,
                'parking_location_id' => $cityMall->id,
                'zone_id' => $zoneA->id,
                'slot_id' => $slotA3->id,
                'booking_date' => Carbon::today(),
                'entry_time' => Carbon::now()->addHour(),
                'expected_exit_time' => Carbon::now()->addHours(3),
                'duration' => 2,
                'amount' => 60.00,
                'booking_status' => Booking::STATUS_CONFIRMED,
                'payment_status' => Booking::PAYMENT_PAID,
                'qr_code' => 'SP202609210001',
            ]
        );

        Payment::updateOrCreate(
            ['booking_id' => $bookingReserved->id],
            [
                'user_id' => $users[0]->id,
                'transaction_id' => 'TXN' . strtoupper(Str::random(10)),
                'payment_method' => 'UPI',
                'amount' => 60.00,
                'status' => Payment::STATUS_SUCCESS,
                'paid_at' => Carbon::now()->subMinutes(15),
            ]
        );

        // 7b. Active/Occupied Booking (for A04)
        $bookingActive = Booking::updateOrCreate(
            ['booking_id' => 'SP202609210002'],
            [
                'user_id' => $users[1]->id,
                'vehicle_id' => $vehicles[2]->id,
                'parking_location_id' => $cityMall->id,
                'zone_id' => $zoneA->id,
                'slot_id' => $slotA4->id,
                'booking_date' => Carbon::today(),
                'entry_time' => Carbon::now()->subHour(),
                'expected_exit_time' => Carbon::now()->addHours(2),
                'duration' => 3,
                'amount' => 90.00,
                'booking_status' => Booking::STATUS_ACTIVE,
                'payment_status' => Booking::PAYMENT_PAID,
                'qr_code' => 'SP202609210002',
            ]
        );

        Payment::updateOrCreate(
            ['booking_id' => $bookingActive->id],
            [
                'user_id' => $users[1]->id,
                'transaction_id' => 'TXN' . strtoupper(Str::random(10)),
                'payment_method' => 'Credit Card',
                'amount' => 90.00,
                'status' => Payment::STATUS_SUCCESS,
                'paid_at' => Carbon::now()->subHours(2),
            ]
        );

        ParkingEntry::updateOrCreate(
            ['booking_id' => $bookingActive->id],
            [
                'vehicle_id' => $vehicles[2]->id,
                'slot_id' => $slotA4->id,
                'entry_time' => Carbon::now()->subHour(),
                'remarks' => 'Entry scanned at North Gate terminal',
            ]
        );

        // 7c. Completed Booking with Exit
        $slotA1 = ParkingSlot::where('zone_id', $zoneA->id)->where('slot_number', 'A01')->first();
        $bookingCompleted = Booking::updateOrCreate(
            ['booking_id' => 'SP202609210003'],
            [
                'user_id' => $users[2]->id,
                'vehicle_id' => $vehicles[3]->id,
                'parking_location_id' => $cityMall->id,
                'zone_id' => $zoneA->id,
                'slot_id' => $slotA1->id,
                'booking_date' => Carbon::yesterday(),
                'entry_time' => Carbon::yesterday()->setHour(10),
                'expected_exit_time' => Carbon::yesterday()->setHour(13),
                'actual_exit_time' => Carbon::yesterday()->setHour(13)->setMinute(15),
                'duration' => 3,
                'amount' => 90.00,
                'booking_status' => Booking::STATUS_COMPLETED,
                'payment_status' => Booking::PAYMENT_PAID,
                'qr_code' => 'SP202609210003',
            ]
        );

        Payment::updateOrCreate(
            ['booking_id' => $bookingCompleted->id],
            [
                'user_id' => $users[2]->id,
                'transaction_id' => 'TXN' . strtoupper(Str::random(10)),
                'payment_method' => 'Debit Card',
                'amount' => 90.00,
                'status' => Payment::STATUS_SUCCESS,
                'paid_at' => Carbon::yesterday()->setHour(9)->setMinute(50),
            ]
        );

        ParkingEntry::updateOrCreate(
            ['booking_id' => $bookingCompleted->id],
            [
                'vehicle_id' => $vehicles[3]->id,
                'slot_id' => $slotA1->id,
                'entry_time' => Carbon::yesterday()->setHour(10),
                'remarks' => 'Normal entry',
            ]
        );

        ParkingExit::updateOrCreate(
            ['booking_id' => $bookingCompleted->id],
            [
                'slot_id' => $slotA1->id,
                'exit_time' => Carbon::yesterday()->setHour(13)->setMinute(15),
                'total_duration' => 195,
                'penalty_charge' => 0.00,
                'total_charge' => 90.00,
                'remarks' => 'Exit validated',
            ]
        );

        // 8. Notifications
        Notification::updateOrCreate(
            ['title' => 'Welcome to SMARTPARK AI'],
            [
                'user_id' => $users[0]->id,
                'message' => 'Welcome to SMARTPARK AI! Search nearby parking locations and reserve your spot in seconds.',
                'type' => 'info',
                'is_read' => true,
            ]
        );

        // 9. System Settings & Dynamic CMS Content
        $settings = [
            // General & Branding
            ['key' => 'site_name', 'value' => 'SMARTPARK AI', 'group' => 'general'],
            ['key' => 'site_title', 'value' => 'AI-Powered Smart Parking Management System', 'group' => 'general'],
            ['key' => 'site_subtitle', 'value' => 'Next-Generation Intelligent Parking Platform with Real-Time Bay Allocation & Dynamic Tariffs', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'support@smartpark.ai', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '+1 (800) 555-PARK', 'group' => 'general'],
            ['key' => 'address', 'value' => '742 Evergreen Terrace, Tech Corridor, Metropolis', 'group' => 'general'],
            ['key' => 'currency', 'value' => 'USD', 'group' => 'general'],
            ['key' => 'currency_symbol', 'value' => '$', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'UTC', 'group' => 'general'],
            ['key' => 'footer_copyright', 'value' => '© 2026 SMARTPARK AI Inc. All rights reserved.', 'group' => 'general'],

            // Tariffs & Pricing
            ['key' => 'default_hourly_rate', 'value' => '20.00', 'group' => 'pricing'],
            ['key' => 'default_daily_rate', 'value' => '150.00', 'group' => 'pricing'],
            ['key' => 'convenience_fee', 'value' => '2.00', 'group' => 'pricing'],
            ['key' => 'overtime_multiplier', 'value' => '1.5', 'group' => 'pricing'],

            // Gate & Operational Rules
            ['key' => 'grace_period_minutes', 'value' => '15', 'group' => 'rules'],
            ['key' => 'cancellation_window_minutes', 'value' => '30', 'group' => 'rules'],
            ['key' => 'min_booking_duration_hours', 'value' => '1', 'group' => 'rules'],
            ['key' => 'max_booking_duration_hours', 'value' => '24', 'group' => 'rules'],
            ['key' => 'parking_rules_text', 'value' => "1. Park strictly within designated bay lines.\n2. Present your digital QR code upon vehicle entry.\n3. Overstaying exceeding the 15-minute grace period incurs a 1.5x hourly rate penalty.\n4. EV Charging bays are reserved exclusively for electric vehicles while actively charging.", 'group' => 'rules'],

            // Homepage CMS
            ['key' => 'hero_title', 'value' => 'Instant Smart Parking at Your Fingertips', 'group' => 'homepage'],
            ['key' => 'hero_description', 'value' => 'Search facilities, inspect live visual slot grids in real time, reserve guaranteed parking bays, and breeze through barrier gates with automated verification.', 'group' => 'homepage'],
            ['key' => 'about_title', 'value' => 'Why Drivers & Operators Love SMARTPARK AI', 'group' => 'homepage'],
            ['key' => 'about_description', 'value' => 'SMARTPARK AI replaces circling crowded lots with seamless pre-booking, live availability boards, instant QR validation, automated overtime billing, and demand analytics.', 'group' => 'homepage'],
        ];

        foreach ($settings as $s) {
            \App\Models\Setting::updateOrCreate(['key' => $s['key']], ['value' => $s['value'], 'group' => $s['group']]);
        }

        $this->call(ParkingServiceSeeder::class);
        $this->call(HowItWorksSeeder::class);
        $this->call(AboutPageSeeder::class);
        $this->call(AdminLoginPageSeeder::class);
    }
}
