<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ParkingLocationsTest extends TestCase
{
    protected Admin $admin;
    protected User $user;

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

        $this->user = User::firstOrCreate(
            ['email' => 'customer_loc@smartpark.com'],
            [
                'name' => 'Customer Tester',
                'password' => bcrypt('password123'),
                'status' => 'Active',
            ]
        );

        // Seed reference locations
        $this->seed(\Database\Seeders\ParkingLocationsSeeder::class);
    }

    public function test_guest_cannot_access_admin_locations(): void
    {
        $response = $this->get('/admin/locations');
        $response->assertRedirect('/admin/login');
    }

    public function test_customer_cannot_access_admin_locations(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/admin/locations');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_locations_index_with_statistics_and_filters(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/locations');
        $response->assertStatus(200);
        $response->assertSee('Parking');
        $response->assertSee('Locations');
        $response->assertSee('Total Locations');
        $response->assertSee('Total Slots');
        $response->assertSee('Available Slots');
        $response->assertSee('Occupied Slots');
        $response->assertSee('City Center Mall');
        $response->assertSee('Airport Parking');
    }

    public function test_admin_search_filter(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/locations?search=Airport');
        $response->assertStatus(200);
        $response->assertSee('Airport Parking');
        $response->assertDontSee('City Center Mall (CG Road)');
    }

    public function test_admin_status_filter(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/locations?status=Inactive');
        $response->assertStatus(200);
        $response->assertSee('University Area');
        $response->assertDontSee('City Center Mall (CG Road)');
    }

    public function test_admin_location_type_filter(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/locations?location_type=Hospital');
        $response->assertStatus(200);
        $response->assertSee('City Care Hospital');
        $response->assertDontSee('Airport Parking (T1 & T2)');
    }

    public function test_admin_can_create_location_with_image(): void
    {
        $fakeImage = UploadedFile::fake()->image('plaza_parking.jpg', 400, 300);
        $uniqueName = 'Grand Plaza Parking ' . uniqid();

        $response = $this->actingAs($this->admin, 'admin')->post('/admin/locations', [
            'name' => $uniqueName,
            'location_type' => 'Commercial',
            'address' => '500 Tech Highway',
            'area' => 'Silicon District',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'pincode' => '380020',
            'opening_time' => '07:00',
            'closing_time' => '23:00',
            'parking_charges' => 35.00,
            'status' => 'Active',
            'image_file' => $fakeImage,
        ]);

        $location = ParkingLocation::where('name', $uniqueName)->first();
        $this->assertNotNull($location);
        $response->assertRedirect('/admin/locations/' . $location->id);
        $this->assertEquals('Commercial', $location->location_type);
        $this->assertStringContainsString('images/locations/', $location->image);
    }

    public function test_admin_can_update_location(): void
    {
        $location = ParkingLocation::where('name', 'City Center Mall')->first();
        $this->assertNotNull($location);

        $response = $this->actingAs($this->admin, 'admin')->put("/admin/locations/{$location->id}", [
            'name' => 'City Center Mega Mall',
            'location_type' => 'Commercial',
            'address' => 'CG Road Updated',
            'city' => 'Ahmedabad',
            'opening_time' => '08:00',
            'closing_time' => '23:30',
            'parking_charges' => 40.00,
            'status' => 'Active',
        ]);

        $response->assertRedirect('/admin/locations');
        $this->assertDatabaseHas('parking_locations', [
            'id' => $location->id,
            'name' => 'City Center Mega Mall',
            'parking_charges' => 40.00,
        ]);
    }

    public function test_safe_deletion_blocks_if_active_bookings_exist(): void
    {
        $location = ParkingLocation::create([
            'name' => 'Delete Protected Mall',
            'location_type' => 'Commercial',
            'address' => '100 Protection Way',
            'city' => 'Ahmedabad',
            'opening_time' => '08:00',
            'closing_time' => '22:00',
            'parking_charges' => 20.00,
            'status' => 'Active',
        ]);

        $zone = ParkingZone::create([
            'parking_location_id' => $location->id,
            'zone_name' => 'Protected Zone',
            'vehicle_type' => 'Car',
            'total_slots' => 10,
        ]);

        $slot = ParkingSlot::create([
            'parking_location_id' => $location->id,
            'zone_id' => $zone->id,
            'slot_number' => 'P-01',
            'status' => ParkingSlot::STATUS_OCCUPIED,
        ]);

        Booking::create([
            'booking_id' => 'BK-PROT-' . uniqid(),
            'user_id' => $this->user->id,
            'parking_location_id' => $location->id,
            'zone_id' => $zone->id,
            'slot_id' => $slot->id,
            'booking_date' => now()->toDateString(),
            'entry_time' => now(),
            'expected_exit_time' => now()->addHours(2),
            'amount' => 50.00,
            'booking_status' => Booking::STATUS_ACTIVE,
            'payment_status' => Booking::PAYMENT_PAID,
        ]);

        $deleteResponse = $this->actingAs($this->admin, 'admin')->delete("/admin/locations/{$location->id}");
        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('error');
        $this->assertDatabaseHas('parking_locations', ['id' => $location->id]);
    }
}
