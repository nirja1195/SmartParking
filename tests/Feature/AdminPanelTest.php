<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Models\Payment;
use App\Models\Staff;
use App\Models\User;
use App\Models\Vehicle;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = Admin::firstOrCreate(
            ['email' => 'admin@smartpark.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'Super Admin',
                'status' => 'Active',
            ]
        );
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
        $response->assertSee('Total Vehicles');
        $response->assertSee('Total Users');
        $response->assertSee('Occupied Slots');
        $response->assertSee('Total Revenue');
    }

    public function test_admin_can_access_all_panel_pages(): void
    {
        $routes = [
            '/admin/parking-locations',
            '/admin/parking-locations/create',
            '/admin/parking-zones',
            '/admin/parking-zones/create',
            '/admin/parking-slots',
            '/admin/availability',
            '/admin/users',
            '/admin/users/create',
            '/admin/vehicles',
            '/admin/staff',
            '/admin/bookings',
            '/admin/bookings?filter=today',
            '/admin/bookings?filter=active',
            '/admin/bookings?filter=history',
            '/admin/entries',
            '/admin/exits',
            '/admin/payments',
            '/admin/transactions',
            '/admin/reports',
            '/admin/reports/bookings',
            '/admin/reports/revenue',
            '/admin/reports/occupancy',
            '/admin/reports/parking',
            '/admin/analytics',
            '/admin/ai-insights',
            '/admin/settings',
            '/admin/profile',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->admin, 'admin')->get($route);
            $this->assertEquals(200, $response->getStatusCode(), "Failed on route: {$route}");
        }
    }

    public function test_admin_can_update_profile_and_settings(): void
    {
        // Update profile
        $response = $this->actingAs($this->admin, 'admin')->put('/admin/profile', [
            'name' => 'Super Admin Updated',
            'email' => $this->admin->email,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'id' => $this->admin->id,
            'name' => 'Super Admin Updated',
        ]);

        // Update settings
        $response = $this->actingAs($this->admin, 'admin')->put('/admin/settings', [
            'group' => 'general',
            'app_name' => 'SMARTPARK AI PLATFORM',
            'currency' => 'USD',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key' => 'app_name',
            'value' => 'SMARTPARK AI PLATFORM',
        ]);
    }

    public function test_complete_end_to_end_parking_lifecycle(): void
    {
        $uniqueId = rand(1000, 9999);
        $locName = "Tech Park {$uniqueId}";
        $zoneName = "Zone A-{$uniqueId}";
        $slotNum = "S-{$uniqueId}";

        // 1. Admin creates Location
        $locResponse = $this->actingAs($this->admin, 'admin')->post('/admin/parking-locations', [
            'name' => $locName,
            'address' => '100 Silicon Blvd',
            'city' => 'San Jose',
            'state' => 'CA',
            'pincode' => '95110',
            'opening_time' => '06:00',
            'closing_time' => '23:00',
            'parking_charges' => 15.00,
            'latitude' => 37.3382,
            'longitude' => -121.8863,
            'status' => 'Active',
        ]);
        $locResponse->assertRedirect();
        $location = ParkingLocation::where('name', $locName)->first();
        $this->assertNotNull($location);

        // 2. Admin creates Zone
        $zoneResponse = $this->actingAs($this->admin, 'admin')->post('/admin/parking-zones', [
            'parking_location_id' => $location->id,
            'zone_name' => $zoneName,
            'zone_code' => "Z{$uniqueId}",
            'vehicle_type' => 'Car',
            'floor' => 'Level 1',
            'section' => 'East',
            'status' => 'Active',
        ]);
        $zoneResponse->assertRedirect();
        $zone = ParkingZone::where('zone_name', $zoneName)->first();
        $this->assertNotNull($zone);

        // 3. Admin creates individual slot with custom price
        $slotResponse = $this->actingAs($this->admin, 'admin')->post('/admin/parking-slots', [
            'zone_id' => $zone->id,
            'slot_number' => $slotNum,
            'slot_type' => 'VIP',
            'price' => 25.00,
            'floor' => 'Level 1',
            'section' => 'East',
            'status' => 'AVAILABLE',
        ]);
        $slotResponse->assertRedirect();
        $slot = ParkingSlot::where('slot_number', $slotNum)->first();
        $this->assertNotNull($slot);
        $this->assertEquals(25.00, $slot->effective_price);

        // 4. Customer books slot
        $user = User::firstOrCreate(
            ['email' => 'driver_test@smartpark.com'],
            ['name' => 'Driver Tester', 'password' => bcrypt('password123'), 'status' => 'active']
        );
        $vehicle = Vehicle::firstOrCreate(
            ['vehicle_number' => 'CA-TEST-99'],
            ['user_id' => $user->id, 'vehicle_type' => 'Car', 'brand' => 'Tesla', 'model' => 'Model 3']
        );

        $booking = Booking::create([
            'booking_id' => 'BK-' . strtoupper(uniqid()),
            'user_id' => $user->id,
            'parking_location_id' => $location->id,
            'zone_id' => $zone->id,
            'slot_id' => $slot->id,
            'vehicle_id' => $vehicle->id,
            'booking_date' => now()->toDateString(),
            'entry_time' => now(),
            'expected_exit_time' => now()->addHours(2),
            'duration' => 2,
            'amount' => 52.00,
            'booking_status' => Booking::STATUS_CONFIRMED,
            'payment_status' => Booking::PAYMENT_PAID,
        ]);
        $slot->update(['status' => ParkingSlot::STATUS_RESERVED]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => 52.00,
            'payment_method' => 'Credit Card',
            'transaction_id' => 'TXN-' . uniqid(),
            'status' => Payment::STATUS_SUCCESS,
        ]);

        $this->assertEquals(ParkingSlot::STATUS_RESERVED, $slot->fresh()->status);

        // 5. Staff/Admin confirms Gate Entry (Vehicle Entry)
        $entryResponse = $this->actingAs($this->admin, 'admin')->post('/admin/entries/confirm', [
            'identifier' => $booking->booking_id,
            'remarks' => 'Gate verified by Admin',
        ]);
        $entryResponse->assertRedirect();
        $this->assertEquals(ParkingSlot::STATUS_OCCUPIED, $slot->fresh()->status);
        $this->assertEquals(Booking::STATUS_ACTIVE, $booking->fresh()->booking_status);

        // 6. Staff/Admin confirms Gate Exit (Vehicle Departure)
        $exitResponse = $this->actingAs($this->admin, 'admin')->post('/admin/exits/confirm', [
            'identifier' => $booking->booking_id,
            'remarks' => 'Smooth exit',
        ]);
        $exitResponse->assertRedirect();
        $this->assertEquals(ParkingSlot::STATUS_AVAILABLE, $slot->fresh()->status);
        $this->assertEquals(Booking::STATUS_COMPLETED, $booking->fresh()->booking_status);
    }
}
