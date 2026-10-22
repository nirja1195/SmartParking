<?php
namespace Tests\Feature;

use App\Mail\AdminLoginCode;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\User;
use App\Services\AdminLoginSecurity as Security;
use App\Support\AdminLoginContent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminLoginExperienceTest extends TestCase
{
    private Admin $admin;
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array', 'cache.default' => 'array', 'mail.default' => 'smtp']);
        DB::purge('sqlite');
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email')->unique(); $table->string('username')->nullable()->unique(); $table->string('password'); $table->string('role'); $table->string('status'); $table->rememberToken(); $table->timestamps();
        });
        Schema::create('settings', function (Blueprint $table) { $table->id(); $table->string('key')->unique(); $table->text('value'); $table->string('group'); $table->timestamps(); });
        $this->admin = Admin::create(['name' => 'Administrator', 'email' => 'admin@admin.com', 'username' => 'admin', 'password' => '12345678', 'role' => 'Super Admin', 'status' => 'active']);
        Mail::fake();
    }

    public function test_reference_login_is_public_and_has_no_exposed_credentials(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('Welcome Back')->assertSee('Login with Admin OTP')->assertSee('Remember me')->assertSee('Tomorrow')->assertDontSee('value="12345678"', false)->assertDontSee('Admin Demo Credentials');
    }

    public function test_requested_credentials_login_and_remember_cookie_work(): void
    {
        $response = $this->post('/admin/login', ['email' => 'admin@admin.com', 'password' => '12345678', 'remember' => '1']);
        $response->assertRedirect('/admin/dashboard')->assertCookie(Auth::guard('admin')->getRecallerName());
        $this->assertAuthenticatedAs($this->admin, 'admin');
        $this->assertTrue(Hash::check('12345678', $this->admin->fresh()->password));
        $this->assertNotSame('12345678', $this->admin->fresh()->password);
    }

    public function test_username_is_accepted_and_wrong_password_is_rejected(): void
    {
        $this->post('/admin/login', ['email' => 'admin', 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest('admin');
        $this->post('/admin/login', ['email' => 'admin', 'password' => '12345678'])->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    public function test_login_is_rate_limited_and_disabled_admin_cannot_login(): void
    {
        for ($i = 0; $i < 5; $i++) $this->post('/admin/login', ['email' => 'admin', 'password' => 'wrong']);
        $response = $this->post('/admin/login', ['email' => 'admin', 'password' => '12345678']);
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many attempts', session('errors')->first('email'));
        $this->assertGuest('admin');
        $this->admin->update(['status' => 'inactive']);
        $this->post('/admin/login', ['email' => 'admin@admin.com', 'password' => '12345678'])->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_customer_cannot_access_admin_login_or_editor(): void
    {
        $customer = new User(['name' => 'Customer', 'email' => 'customer@example.test']); $customer->id = 1;
        $this->actingAs($customer, 'web');
        $this->get('/admin/login')->assertForbidden();
        $this->post('/admin/login', ['email' => 'admin@admin.com', 'password' => '12345678'])->assertForbidden();
        $this->get('/admin/login-page')->assertForbidden();
    }

    public function test_editor_is_protected_and_changes_are_persisted_and_escaped(): void
    {
        $this->get('/admin/login-page')->assertRedirect('/admin/login');
        $this->put('/admin/login-page', ['content' => AdminLoginContent::defaults()])->assertRedirect('/admin/login');
        $this->actingAs($this->admin, 'admin')->get('/admin/login-page')->assertOk();
        $content = AdminLoginContent::defaults(); $content['form']['title'] = '<script>alert(1)</script>';
        $this->put('/admin/login-page', ['content' => $content])->assertSessionHasNoErrors();
        $this->get('/admin/login-page/preview')->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->assertSame($content, AdminLoginContent::current());
        $content['form']['contact_url'] = 'javascript:alert(1)';
        $this->put('/admin/login-page', ['content' => $content])->assertSessionHasErrors('content.form.contact_url');
    }

    public function test_background_upload_is_saved_and_executable_upload_is_rejected(): void
    {
        $this->actingAs($this->admin, 'admin');
        $this->put('/admin/login-page', ['content' => AdminLoginContent::defaults(), 'background' => UploadedFile::fake()->image('parking.png', 80, 120)])->assertSessionHasNoErrors();
        $path = AdminLoginContent::current()['hero']['image'];
        try { $this->assertStringStartsWith('uploads/admin-login/', $path); $this->assertFileExists(public_path($path)); }
        finally { if (is_file(public_path($path))) unlink(public_path($path)); }
        $this->put('/admin/login-page', ['content' => AdminLoginContent::defaults(), 'logo' => UploadedFile::fake()->create('shell.php', 1, 'text/plain')])->assertSessionHasErrors('logo');
    }

    public function test_otp_is_hashed_single_use_and_logs_admin_in(): void
    {
        $this->post('/admin/login/otp', ['email' => $this->admin->email])->assertRedirect('/admin/login/otp');
        $challenge = session('admin_otp_challenge'); $code = Mail::sent(AdminLoginCode::class)->first()->code;
        $cached = Security::cache()->get('admin-otp:'.$challenge);
        $this->assertNotSame($code, $cached['hash']); $this->assertTrue(Hash::check($code, $cached['hash']));
        $this->post('/admin/login/otp/verify', ['code' => $code])->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin, 'admin');
        $this->assertNull(Security::cache()->get('admin-otp:'.$challenge));
        Auth::guard('admin')->logout();
        $this->withSession(['admin_otp_challenge' => $challenge])->post('/admin/login/otp/verify', ['code' => $code])->assertSessionHasErrors('code');
    }

    public function test_otp_expires_without_sliding_expiry_and_locks_after_five_failures(): void
    {
        $this->post('/admin/login/otp', ['email' => $this->admin->email]);
        $challenge = session('admin_otp_challenge'); $key = 'admin-otp:'.$challenge;
        $expires = Security::cache()->get($key)['expires_at'];
        $this->travel(5)->minutes();
        $this->post('/admin/login/otp/verify', ['code' => '000000'])->assertSessionHasErrors('code');
        $this->assertSame($expires, Security::cache()->get($key)['expires_at']);
        for ($i = 0; $i < 4; $i++) $this->post('/admin/login/otp/verify', ['code' => '000000']);
        $this->assertNull(Security::cache()->get($key));
        $this->post('/admin/login/otp', ['email' => $this->admin->email]);
        $code = Mail::sent(AdminLoginCode::class)->last()->code;
        $this->travel(11)->minutes();
        $this->post('/admin/login/otp/verify', ['code' => $code])->assertSessionHasErrors('code');
        $this->assertGuest('admin');
    }

    public function test_resending_invalidates_previous_code_and_unknown_email_is_private(): void
    {
        $this->post('/admin/login/otp', ['email' => $this->admin->email]); $old = session('admin_otp_challenge');
        $knownMessage = session('success');
        $this->post('/admin/login/otp', ['email' => $this->admin->email]);
        $this->assertNull(Security::cache()->get('admin-otp:'.$old));
        $this->post('/admin/login/otp', ['email' => 'unknown@example.test']);
        $this->assertSame($knownMessage, session('success'));
        Mail::assertSentCount(2);
    }

    public function test_username_can_be_updated_in_profile_and_used_for_login(): void
    {
        $this->actingAs($this->admin, 'admin');
        $this->put('/admin/profile', ['name' => 'Administrator', 'email' => 'admin@admin.com', 'username' => 'parking.admin'])->assertSessionHasNoErrors();
        $this->assertSame('parking.admin', $this->admin->fresh()->username);
        Auth::guard('admin')->logout();
        $this->post('/admin/login', ['email' => 'parking.admin', 'password' => '12345678'])->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    public function test_log_mailer_does_not_pretend_to_deliver_email(): void
    {
        config(['mail.default' => 'log']);
        $this->get('/admin/login/otp')->assertOk()->assertSee('Email delivery is not configured yet');
        $this->post('/admin/login/otp', ['email' => $this->admin->email])->assertSessionHas('error');
        Mail::assertNothingSent();
    }
}
