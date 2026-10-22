<?php
namespace Tests\Feature;

use App\Models\{Admin, Booking, ParkingLocation, ParkingSlot, ParkingZone, User, Vehicle};
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminBookingsPageTest extends TestCase
{
    private ParkingSlot $slot;
    private Vehicle $vehicle;
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>':memory:', 'session.driver'=>'array', 'cache.default'=>'array']);
        DB::purge('sqlite');
        foreach (glob(database_path('migrations/2024_01_01_*.php')) as $file) (require $file)->up();
        $admin = new Admin(['name'=>'Admin','role'=>'Administrator','status'=>'active']); $admin->id=1;
        $this->actingAs($admin, 'admin');
        $user = User::create(['name'=>'Real Customer','email'=>'customer@example.test','password'=>bcrypt('test-password'),'status'=>'active']);
        $location = ParkingLocation::create(['name'=>'Test Facility','address'=>'Road','city'=>'Surat','status'=>'Active','parking_charges'=>30]);
        $zone = ParkingZone::create(['parking_location_id'=>$location->id,'zone_name'=>'A','vehicle_type'=>'Car','floor'=>'Ground','status'=>'Active']);
        $this->slot = ParkingSlot::create(['parking_location_id'=>$location->id,'zone_id'=>$zone->id,'slot_number'=>'A-01','vehicle_type'=>'Car','floor'=>'Ground','status'=>'AVAILABLE']);
        $this->vehicle = Vehicle::create(['user_id'=>$user->id,'vehicle_number'=>'GJ01TEST','vehicle_type'=>'Car','status'=>'active']);
    }
    private function payload(): array
    {
        return ['slot_id'=>$this->slot->id,'vehicle_id'=>$this->vehicle->id,'entry_time'=>now()->addDay()->startOfHour()->toDateTimeString(),'expected_exit_time'=>now()->addDay()->startOfHour()->addHours(2)->toDateTimeString()];
    }
    public function test_empty_board_has_real_zero_counts(): void
    {
        $this->get('/admin/bookings')->assertOk()->assertSee('No bookings found')->assertSee('Showing 0 to 0 of 0 bookings')->assertViewHas('metrics', fn($metrics)=>array_sum(array_column($metrics,'count'))===0);
    }
    public function test_admin_can_create_a_booking_with_computed_amount_and_owner(): void
    {
        $this->post('/admin/bookings',$this->payload())->assertRedirect(route('admin.bookings.index'))->assertSessionHasNoErrors();
        $booking=Booking::firstOrFail();
        $this->assertSame('60.00',$booking->amount);
        $this->assertSame($this->vehicle->user_id,$booking->user_id);
        $this->get('/admin/bookings')->assertOk()->assertSee($booking->booking_id)->assertSee('Real Customer')->assertSee('GJ01TEST')->assertSee('60.00');
    }
    public function test_status_search_and_date_filters_use_saved_records(): void
    {
        $this->post('/admin/bookings',$this->payload())->assertSessionHasNoErrors();
        Booking::query()->update(['booking_status'=>'ACTIVE']);
        $this->get('/admin/bookings?search=GJ01TEST&booking_status=ACTIVE&date_from='.now()->addDay()->toDateString().'&date_to='.now()->addDay()->toDateString())->assertOk()->assertViewHas('bookings',fn($rows)=>$rows->total()===1)->assertViewHas('metrics',fn($metrics)=>$metrics[1]['count']===1);
        $this->get('/admin/bookings?booking_status=COMPLETED')->assertOk()->assertSee('No bookings found');
        $this->get('/admin/bookings?date_from=2026-09-10&date_to=2026-09-01')->assertSessionHasErrors('date_to');
    }
    public function test_unavailable_slots_and_invalid_times_are_rejected(): void
    {
        $this->slot->update(['status'=>'OCCUPIED']);
        $this->post('/admin/bookings',$this->payload())->assertSessionHasErrors('booking');
        $data=$this->payload(); $data['expected_exit_time']=$data['entry_time'];
        $this->post('/admin/bookings',$data)->assertSessionHasErrors('expected_exit_time');
        $this->assertSame(0,Booking::count());
    }
}