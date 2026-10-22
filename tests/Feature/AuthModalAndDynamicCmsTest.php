<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ParkingLocation;
use App\Models\ParkingService;
use App\Models\User;
use Tests\TestCase;

class AuthModalAndDynamicCmsTest extends TestCase
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
                'status' => 'active',
            ]
        );

        $this->admin = Admin::firstOrCreate(
            ['email' => 'admin@smartpark.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'Super Admin',
                'status' => 'active',
            ]
        );
    }

    /**
     * Test home page renders auth modal markup, dynamic services and locations from database.
     */
    public function test_home_page_renders_modal_and_dynamic_data(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Assert Auth Modal elements
        $response->assertSee('authModalBackdrop');
        $response->assertSee('authModalCard');
        $response->assertSee('loginModalForm');
        $response->assertSee('registerModalForm');
        $response->assertSee('forgotModalForm');
        $response->assertSee('Welcome to SmartParking');
        $response->assertSee('openAuthModal');

        // Assert dynamic services
        $response->assertSee('Car Parking');
        $response->assertSee('Bike Parking');
        $response->assertSee('EV Charging');

        // Assert dynamic locations
        $response->assertSee('Available Parking Facilities');
    }

    /**
     * Test AJAX login returns JSON and authenticates user.
     */
    public function test_ajax_modal_login_success(): void
    {
        $response = $this->postJson('/login', [
            'login' => 'driver@smartpark.com',
            'password' => 'password123',
            'intended_action' => 'find-parking',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect_url' => route('user.find-parking'),
        ]);

        $this->assertAuthenticatedAs($this->user, 'web');
    }

    /**
     * Test AJAX login with invalid credentials returns 422 JSON.
     */
    public function test_ajax_modal_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/login', [
            'login' => 'driver@smartpark.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['login']);
    }

    /**
     * Test AJAX modal registration creates and logs in user.
     */
    public function test_ajax_modal_register_success(): void
    {
        $uniqueEmail = 'newuser' . time() . '@smartpark.com';

        $response = $this->postJson('/register', [
            'name' => 'Modal Registered User',
            'email' => $uniqueEmail,
            'phone' => '9988776655',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'intended_action' => 'find-parking',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect_url' => route('user.find-parking'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $uniqueEmail,
            'name' => 'Modal Registered User',
        ]);
    }

    /**
     * Test AJAX forgot password returns JSON success message.
     */
    public function test_ajax_modal_forgot_password(): void
    {
        $response = $this->postJson('/forgot-password', [
            'email' => 'driver@smartpark.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    /**
     * Test Admin can manage dynamic parking services.
     */
    public function test_admin_can_manage_parking_services(): void
    {
        // View services
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/parking-services');
        $response->assertStatus(200);
        $response->assertSee('Dynamic Parking Services');

        // Create new service
        $createResponse = $this->actingAs($this->admin, 'admin')->post('/admin/parking-services', [
            'name' => 'Test Valet Parking',
            'description' => 'Test valet service description',
            'icon' => 'bi bi-shield-check',
            'image' => 'images/landing/service-car.png',
            'sort_order' => 99,
            'status' => 'Active',
        ]);
        $createResponse->assertRedirect();
        $this->assertDatabaseHas('parking_services', ['name' => 'Test Valet Parking']);

        // Toggle status
        $service = ParkingService::where('name', 'Test Valet Parking')->first();
        $this->actingAs($this->admin, 'admin')->post("/admin/parking-services/{$service->id}/toggle-status");
        $this->assertDatabaseHas('parking_services', ['id' => $service->id, 'status' => 'Inactive']);

        // Delete service
        $this->actingAs($this->admin, 'admin')->delete("/admin/parking-services/{$service->id}");
        $this->assertDatabaseMissing('parking_services', ['id' => $service->id]);
    }

    /**
     * Test Admin can view Homepage CMS manager.
     */
    public function test_admin_can_access_homepage_cms(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/homepage');
        $response->assertStatus(200);
        $response->assertSee('Home Page CMS Manager');
        $response->assertSee('Branding & Header', false);
        $response->assertSee('Hero Section');
        $response->assertSee('Save Homepage Changes');
    }

    /**
     * Test Admin can update homepage CMS settings and see them reflected on the public landing page.
     */
    public function test_admin_can_update_homepage_cms_and_see_changes_on_landing_page(): void
    {
        // 1. Admin saves custom homepage content
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/homepage', [
            'site_brand_first' => 'NextPark',
            'site_brand_second' => 'AI',
            'app_tagline' => 'Intelligent Urban Spaces.',
            'hero_title_line1' => 'Find &amp; Book',
            'hero_title_line2' => 'Slot',
            'hero_title_highlight' => 'Effortlessly',
            'hero_subtitle' => 'Revolutionary AI parking management in real time.',
            'banner_title' => 'Sustainable Urban Smart City',
            'banner_desc' => 'Eco-friendly urban parking infrastructure.',
            'footer_copyright_text' => 'NextPark Global. All Rights Reserved.',
        ]);

        $response->assertRedirect(route('admin.homepage.index'));
        $response->assertSessionHas('success');

        // 2. Verify settings in database
        $this->assertDatabaseHas('settings', ['key' => 'site_brand_first', 'value' => 'NextPark']);
        $this->assertDatabaseHas('settings', ['key' => 'banner_title', 'value' => 'Sustainable Urban Smart City']);

        // 3. Public landing page displays the updated content immediately without code changes
        $publicResponse = $this->get('/');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('NextPark');
        $publicResponse->assertSee('Intelligent Urban Spaces.');
        $publicResponse->assertSee('Effortlessly');
        $publicResponse->assertSee('Sustainable Urban Smart City');
        $publicResponse->assertSee('NextPark Global. All Rights Reserved.');

        // Clean up settings to original defaults so other tests remain unaffected
        \App\Models\Setting::set('site_brand_first', 'Smart');
        \App\Models\Setting::set('site_brand_second', 'Parking');
        \App\Models\Setting::set('app_tagline', 'Park Smarter. Live Easier.');
        \App\Models\Setting::set('hero_title_line1', 'Find &amp; Book');
        \App\Models\Setting::set('hero_title_line2', 'Parking');
        \App\Models\Setting::set('hero_title_highlight', 'Instantly');
        \App\Models\Setting::set('hero_subtitle', 'Smart parking for a smarter tomorrow. Save time, avoid hassle and book your parking slot in just a few clicks.');
        \App\Models\Setting::set('banner_title', 'A Cleaner, Smarter City');
        \App\Models\Setting::set('banner_desc', "Let's make urban living easier, together.");
        \App\Models\Setting::set('footer_copyright_text', 'All Rights Reserved.');
    }
}
