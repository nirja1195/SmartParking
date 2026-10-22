<?php
namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ParkingSlotBoardTest extends TestCase
{
    private Admin $admin;
    private ParkingLocation $location;
    private ParkingZone $zone;
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array','cache.default'=>'array']);
        DB::purge('sqlite');
        Schema::create('settings', function(Blueprint $t){$t->id();$t->string('key');$t->text('value')->nullable();$t->string('group');$t->timestamps();});
        Schema::create('notifications',function(Blueprint $t){$t->id();$t->integer('admin_id');$t->boolean('is_read')->default(false);$t->string('title');$t->text('message');$t->timestamps();});
        foreach(['2024_01_01_000003_create_parking_locations_table.php','2024_01_01_000005_create_parking_zones_table.php','2024_01_01_000006_create_parking_slots_table.php'] as $migration) (require database_path('migrations/'.$migration))->up();
        Schema::table('parking_slots',function(Blueprint $t){$t->string('slot_type')->default('Standard');$t->decimal('price',8,2)->nullable();$t->text('description')->nullable();});
        Schema::create('bookings',function(Blueprint $t){$t->id();$t->integer('slot_id');$t->string('booking_status');});
        $this->admin = new Admin(['name'=>'Admin','email'=>'admin@example.test','role'=>'Administrator','status'=>'active']);$this->admin->id=1;
        $this->location = ParkingLocation::create(['name'=>'City Center Mall','address'=>'Main Road','city'=>'Surat','status'=>'Active']);
        $this->zone = ParkingZone::create(['parking_location_id'=>$this->location->id,'zone_name'=>'Zone A','vehicle_type'=>'Car','floor'=>'Ground','status'=>'Active']);
        $this->slot('A-01');$this->slot('A-02',['status'=>'OCCUPIED']);
        $this->slot('EV-01',['vehicle_type'=>'Car','slot_type'=>'EV Charging']);
        $this->slot('H-01',['slot_type'=>'Disabled']);
        $this->slot('B-01',['vehicle_type'=>'Bike','floor'=>'Level 1']);
    }

    private function slot(string $number,array $extra=[]): ParkingSlot
    {
        return ParkingSlot::create(array_merge(['parking_location_id'=>$this->location->id,'zone_id'=>$this->zone->id,'slot_number'=>$number,'vehicle_type'=>'Car','floor'=>'Ground','status'=>'AVAILABLE','slot_type'=>'Standard'],$extra));
    }

    public function test_board_requires_admin_authentication(): void
    {
        $this->get('/admin/parking-slots')->assertRedirect('/admin/login');
        $this->getJson('/admin/parking-slots')->assertUnauthorized();
    }

    public function test_board_uses_real_exclusive_category_counts_and_floor_groups(): void
    {
        $response=$this->actingAs($this->admin,'admin')->get('/admin/parking-slots');
        $response->assertOk()->assertSee('City Center Mall')->assertSee('Slots - Ground Floor')->assertSee('Slots - First Floor')->assertSee('A-01')->assertSee('Total Slots: 4')->assertSee('Total Slots: 1');
        $response->assertViewHas('stats',fn($stats)=>$stats['car']===['total'=>2,'available'=>1]&&$stats['ev']['total']===1&&$stats['handicap']['total']===1&&$stats['bike']['total']===1);
    }

    public function test_search_status_type_and_floor_filters_combine_without_changing_totals(): void
    {
        $this->actingAs($this->admin,'admin');
        $this->get('/admin/parking-slots?type=car&status=AVAILABLE&search=A-01&floor=Ground%20Floor')->assertOk()->assertViewHas('slots',fn($slots)=>$slots->pluck('slot_number')->values()->all()===['A-01'])->assertViewHas('stats',fn($stats)=>$stats['car']['total']===2);
        $this->get('/admin/parking-slots?type=handicap')->assertViewHas('slots',fn($slots)=>$slots->pluck('slot_number')->values()->all()===['H-01']);
        $this->get('/admin/parking-slots?floor=First%20Floor')->assertViewHas('slots',fn($slots)=>$slots->pluck('slot_number')->values()->all()===['B-01']);
        $this->get('/admin/parking-slots?search=does-not-exist')->assertSee('No parking slots found');
    }

    public function test_location_switch_does_not_mix_slots_or_totals(): void
    {
        $other=ParkingLocation::create(['name'=>'Airport','address'=>'Airport Road','city'=>'Surat','status'=>'Active']);
        $this->slot('OTHER-01',['parking_location_id'=>$other->id]);
        $this->actingAs($this->admin,'admin')->get('/admin/parking-slots?location_id='.$other->id)->assertOk()->assertViewHas('slots',fn($slots)=>$slots->pluck('slot_number')->values()->all()===['OTHER-01'])->assertViewHas('stats',fn($stats)=>$stats['car']['total']===1);
    }

    public function test_json_refresh_shows_persisted_status_changes(): void
    {
        $slot=ParkingSlot::where('slot_number','A-01')->first();
        $this->actingAs($this->admin,'admin')->patch('/admin/parking-slots/'.$slot->id.'/status',['status'=>'MAINTENANCE'])->assertSessionHasNoErrors();
        $this->assertSame('MAINTENANCE',$slot->fresh()->status);
        $this->getJson('/admin/parking-slots')->assertOk()->assertJsonStructure(['summary','board'])->assertSee('Maintenance');
    }

    public function test_create_edit_and_batch_generation_remain_functional(): void
    {
        $this->actingAs($this->admin,'admin');
        $this->post('/admin/parking-slots',['zone_id'=>$this->zone->id,'slot_number'=>'z-01','status'=>'AVAILABLE'])->assertSessionHasNoErrors();
        $slot=ParkingSlot::where('slot_number','Z-01')->firstOrFail();
        $this->put('/admin/parking-slots/'.$slot->id,['slot_number'=>'Z-01','status'=>'RESERVED','slot_type'=>'Disabled','floor'=>'Level 1','price'=>30])->assertSessionHasNoErrors();
        $this->assertSame('RESERVED',$slot->fresh()->status);
        $this->assertSame('Disabled',$slot->fresh()->slot_type);
        $this->post('/admin/parking-slots/batch',['zone_id'=>$this->zone->id,'prefix'=>'C-','start'=>1,'count'=>3])->assertSessionHasNoErrors();
        $this->assertSame(3,ParkingSlot::where('slot_number','like','C-%')->count());
    }

    public function test_duplicates_and_deletion_of_booked_slots_are_rejected(): void
    {
        $this->actingAs($this->admin,'admin');
        $this->post('/admin/parking-slots',['zone_id'=>$this->zone->id,'slot_number'=>'a-01','status'=>'AVAILABLE'])->assertSessionHasErrors('slot_number');
        $slot=ParkingSlot::where('slot_number','A-01')->firstOrFail();
        DB::table('bookings')->insert(['slot_id'=>$slot->id,'booking_status'=>'COMPLETED']);
        $this->delete('/admin/parking-slots/'.$slot->id)->assertSessionHasErrors('slot');
        $this->assertNotNull($slot->fresh());
        $unused=ParkingSlot::where('slot_number','B-01')->firstOrFail();
        $this->delete('/admin/parking-slots/'.$unused->id)->assertSessionHasNoErrors();
        $this->assertNull($unused->fresh());
    }

    public function test_empty_location_never_displays_sample_slots_or_counts(): void
    {
        ParkingSlot::query()->delete();
        $response = $this->actingAs($this->admin, 'admin')->getJson('/admin/parking-slots')
            ->assertOk()->assertJsonPath('floors', [])
            ->assertSee('No parking slots found')
            ->assertDontSee('A-01');
        $this->assertStringContainsString('0 / 0', $response->json('summary'));
        $this->assertStringNotContainsString('120 / 200', $response->json('summary'));
    }

    public function test_live_refresh_updates_floors_from_saved_records(): void
    {
        $slot = ParkingSlot::where('slot_number', 'A-01')->firstOrFail();
        $slot->update(['floor' => 'Roof']);
        $response = $this->actingAs($this->admin, 'admin')->getJson('/admin/parking-slots');
        $response->assertOk();
        $this->assertContains('Roof', $response->json('floors'));
        $this->zone->update(['floor' => '']);
        $slot->update(['floor' => '']);
        $this->assertSame('Unassigned Floor', \App\Support\ParkingSlotBoard::floor($slot->fresh()));
    }
    public function test_empty_installation_and_invalid_filters_have_clear_responses(): void
    {
        ParkingSlot::query()->delete();ParkingZone::query()->delete();ParkingLocation::query()->delete();
        $this->actingAs($this->admin,'admin')->get('/admin/parking-slots')->assertOk()->assertSee('No parking locations yet');
        $this->getJson('/admin/parking-slots?type=invalid')->assertUnprocessable();
    }
}
