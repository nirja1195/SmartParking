<?php
namespace Tests\Feature;

use App\Models\{Admin, User};
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminUsersPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array','cache.default'=>'array']);
        DB::purge('sqlite');
        foreach(glob(database_path('migrations/2024_01_01_*.php')) as $file) (require $file)->up();
        $admin = new Admin(['name'=>'Admin','role'=>'Administrator','status'=>'active']); $admin->id=1;
        $this->actingAs($admin,'admin');
    }
    private function user(string $email, string $status='active'): User
    {
        return User::create(['name'=>'Test Customer','email'=>$email,'phone'=>'9876543210','status'=>$status,'password'=>bcrypt('password123')]);
    }
    public function test_empty_page_has_zero_counts_without_dummy_users(): void
    {
        $this->get('/admin/users')->assertOk()->assertSee('No users found')->assertSee('Showing 0 to 0 of 0 users')->assertViewHas('metrics',fn($metrics)=>array_sum(array_column($metrics,'count'))===0);
    }
    public function test_metrics_and_combined_filters_use_database_records(): void
    {
        $this->user('active@example.test');
        $inactive=$this->user('inactive@example.test','inactive');
        $inactive->created_at=now()->subMonthNoOverflow();$inactive->save();
        $this->get('/admin/users?search=active%40&status=active&role=user&joined_date='.now()->toDateString())->assertOk()->assertViewHas('users',fn($users)=>$users->total()===1)->assertViewHas('metrics',fn($metrics)=>array_column($metrics,'count')===[2,1,1,1]);
        $this->get('/admin/users?status=blocked')->assertSee('No users found');
        $this->get('/admin/users?joined_date=invalid')->assertSessionHasErrors('joined_date');
    }
    public function test_pagination_has_ten_rows_and_retains_filters(): void
    {
        for($i=0;$i<12;$i++) $this->user("user{$i}@example.test");
        $this->get('/admin/users?status=active')->assertOk()->assertSee('Showing 1 to 10 of 12 users')->assertViewHas('users',fn($users)=>$users->count()===10 && str_contains($users->nextPageUrl(),'status=active'));
        $this->get('/admin/users?status=active&page=2')->assertOk()->assertSee('Showing 11 to 12 of 12 users');
    }
    public function test_status_and_existing_crud_actions_remain_functional(): void
    {
        $user=$this->user('manage@example.test');
        $this->get('/admin/users/create')->assertOk();
        $this->get('/admin/users/'.$user->id.'/edit')->assertOk();
        $this->get('/admin/users/'.$user->id)->assertOk();
        $this->patch('/admin/users/'.$user->id.'/status',['status'=>'inactive'])->assertSessionHasNoErrors();
        $this->assertSame('inactive',$user->fresh()->status);
        $this->delete('/admin/users/'.$user->id)->assertRedirect(route('admin.users.index'));
        $this->assertNull($user->fresh());
    }
}