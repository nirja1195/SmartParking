<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;

class LandingPageAndSeparationTest extends TestCase
{
    protected User $user;
    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::firstOrCreate(
            ['email' => 'driver@smartpark.com'],
            [
                'name' => 'John Driver',
                'phone' => '1234567890',
                'password' => bcrypt('password123'),
                'status' => 'Active',
            ]
        );

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

    /**
     * Test public home page loads without authentication and has correct elements without admin links.
     */
    public function test_public_home_page_loads_and_does_not_redirect(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Find &amp; Book', false);
        $response->assertSee('Instantly', false);
        $response->assertSee('Find Parking');
        $response->assertSee('Park Smarter. Live Easier.');
        
        // Assert no admin links or buttons in landing page navigation
        $response->assertDontSee('/admin/login');
        $response->assertDontSee('/admin/dashboard');
        $response->assertDontSee('Admin Portal');
    }

    /**
     * Test unauthenticated visitor accessing /find-parking is redirected to /login.
     */
    public function test_guest_accessing_find_parking_redirects_to_login(): void
    {
        $response = $this->get('/find-parking');
        $response->assertRedirect('/login');
    }

    /**
     * Test customer login redirects to intended destination (/find-parking).
     */
    public function test_customer_login_redirects_to_intended_find_parking(): void
    {
        // First visit protected page to set intended URL
        $this->get('/find-parking');

        // Post login credentials
        $response = $this->post('/login', [
            'email' => 'driver@smartpark.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/find-parking');
    }

    /**
     * Test logged-in customer accessing /find-parking directly succeeds.
     */
    public function test_logged_in_customer_can_access_find_parking(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/find-parking');
        $response->assertStatus(200);
    }

    /**
     * Test authenticated customer accessing /admin/dashboard receives 403 Forbidden.
     */
    public function test_authenticated_customer_denied_access_to_admin_dashboard(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Test authenticated customer accessing /admin/login receives 403 Forbidden.
     */
    public function test_authenticated_customer_denied_access_to_admin_login(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/admin/login');
        $response->assertStatus(403);
    }

    /**
     * Test guest accessing /admin/dashboard redirects to /admin/login.
     */
    public function test_guest_accessing_admin_dashboard_redirects_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test admin can access admin login page as guest and dashboard when authenticated.
     */
    public function test_admin_can_access_admin_portal(): void
    {
        // Guest can see admin login
        $loginResponse = $this->get('/admin/login');
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('Login');

        // Admin can access admin dashboard
        $dashResponse = $this->actingAs($this->admin, 'admin')->get('/admin/dashboard');
        $dashResponse->assertStatus(200);
    }
}
