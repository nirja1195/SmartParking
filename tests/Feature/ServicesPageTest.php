<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ParkingService;
use App\Models\ServicePageFeature;
use App\Models\ServicePageSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ServicesPageTest extends TestCase
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
                'status' => 'active',
            ]
        );

        // Ensure default settings and features exist
        ServicePageSetting::getSettings();
        if (ServicePageFeature::count() === 0) {
            $this->seed(\Database\Seeders\ServicePageSeeder::class);
        }
        if (ParkingService::count() < 6) {
            $this->seed(\Database\Seeders\ParkingServiceSeeder::class);
        }

        // Clean up any residual test records from prior runs
        ParkingService::whereIn('slug', ['automated-robot-valet', 'automated-ai-valet-pro', 'secret-test-service'])->delete();
    }

    /**
     * Test public /services page loads with 200 status and does not require authentication.
     */
    public function test_services_page_is_public_and_loads_successfully(): void
    {
        $response = $this->get('/services');
        $response->assertStatus(200);

        // Header and Active Underline
        $response->assertSee('nav-link-custom active', false);
        $response->assertSee('Services');
        $response->assertSee('Park Smarter. Live Easier.');

        // Hero Section
        $response->assertSee('OUR SERVICES');
        $response->assertSee('Parking Services');
        $response->assertSee('Every Need');
        $response->assertSee('Choose the right parking solution');

        // All 6 Seeded Services
        $response->assertSee('Car Parking');
        $response->assertSee('Bike Parking');
        $response->assertSee('EV Charging');
        $response->assertSee('Accessible Parking');
        $response->assertSee('Hourly / Daily Parking');
        $response->assertSee('Premium Parking');

        // Search Input
        $response->assertSee('servicesSearchInput');
        $response->assertSee('Search services...');

        // Custom Solution Banner
        $response->assertSee('Need a Custom Parking Solution?');
        $response->assertSee('Contact us for corporate, event or bulk parking requirements.');
        $response->assertSee('Contact Us');

        // Feature Strip (4 items)
        $response->assertSee('Safe &amp; Secure', false);
        $response->assertSee('Multiple Locations');
        $response->assertSee('Easy Payments');
        $response->assertSee('24/7 Support');

        // Dark Footer
        $response->assertSee('Smart Parking makes urban living easier');
        $response->assertSee('Quick Links');
        $response->assertSee('Download Our App');
        $response->assertSee('A Smarter Way to Park');
    }

    /**
     * Test individual service detail page.
     */
    public function test_service_detail_page_loads_with_correct_slug(): void
    {
        $response = $this->get('/services/car-parking');
        $response->assertStatus(200);
        $response->assertSee('Car Parking');
        $response->assertSee('Safe and secure parking for your car.');
        $response->assertSee('Book Parking For This Service');
    }

    /**
     * Test inactive service is hidden from the public /services page.
     */
    public function test_inactive_service_does_not_appear_on_public_services_page(): void
    {
        $service = ParkingService::firstOrCreate(
            ['name' => 'Secret Test Service'],
            [
                'slug' => 'secret-test-service',
                'description' => 'This should be hidden',
                'status' => 'Inactive',
                'sort_order' => 999,
            ]
        );

        $response = $this->get('/services');
        $response->assertStatus(200);
        $response->assertDontSee('Secret Test Service');

        // Cleanup
        $service->delete();
    }

    /**
     * Test admin can access the Admin Services management panel.
     */
    public function test_admin_can_access_services_management_panel(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/services');
        $response->assertStatus(200);
        $response->assertSee('Services Management');
        $response->assertSee('Parking Services');
        $response->assertSee('Feature Strip');
    }

    /**
     * Test admin can create, edit, upload image, and delete a service.
     */
    public function test_admin_can_crud_parking_service_with_image_upload(): void
    {
        // 1. Create with uploaded image
        $fakeImage = UploadedFile::fake()->image('valet_service.png', 300, 200);

        $storeResponse = $this->actingAs($this->admin, 'admin')->post('/admin/services', [
            'name' => 'Automated Robot Valet',
            'slug' => 'automated-robot-valet',
            'short_description' => 'Futuristic robotic parking.',
            'description' => 'Fully automated robotic parking system.',
            'icon' => 'bi bi-robot',
            'image_file' => $fakeImage,
            'button_text' => 'Try Valet',
            'status' => 'Active',
            'sort_order' => 10,
        ]);

        $storeResponse->assertRedirect(route('admin.parking-services.index'));
        $this->assertDatabaseHas('parking_services', ['name' => 'Automated Robot Valet']);

        $service = ParkingService::where('name', 'Automated Robot Valet')->first();
        $this->assertNotNull($service);
        $this->assertStringContainsString('uploads/services/', $service->image);

        // Check it appears on public page
        $publicRes = $this->get('/services');
        $publicRes->assertSee('Automated Robot Valet');

        // 2. Update service
        $updateResponse = $this->actingAs($this->admin, 'admin')->put("/admin/services/{$service->id}", [
            'name' => 'Automated AI Valet Pro',
            'slug' => 'automated-ai-valet-pro',
            'short_description' => 'Updated AI valet description.',
            'description' => 'Updated full description.',
            'icon' => 'bi bi-cpu',
            'image' => $service->image,
            'status' => 'Active',
            'sort_order' => 10,
        ]);

        $updateResponse->assertRedirect(route('admin.parking-services.index'));
        $this->assertDatabaseHas('parking_services', ['name' => 'Automated AI Valet Pro']);

        // 3. Toggle Status to Inactive
        $this->actingAs($this->admin, 'admin')->post("/admin/services/{$service->id}/toggle-status");
        $this->assertDatabaseHas('parking_services', ['id' => $service->id, 'status' => 'Inactive']);

        // Inactive should disappear from public page
        $hiddenRes = $this->get('/services');
        $hiddenRes->assertDontSee('Automated AI Valet Pro');

        // 4. Delete
        $deleteResponse = $this->actingAs($this->admin, 'admin')->delete("/admin/services/{$service->id}");
        $deleteResponse->assertRedirect(route('admin.parking-services.index'));
        $this->assertDatabaseMissing('parking_services', ['id' => $service->id]);
    }

    /**
     * Test admin can update Services Page settings (Hero, Banner, SEO) and see changes live.
     */
    public function test_admin_can_update_service_page_settings(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/services/page-settings', [
            'hero_eyebrow' => 'SPECIALIZED AMENITIES',
            'hero_title' => 'World-Class Parking',
            'hero_highlight' => 'Tailored For You',
            'hero_description' => 'Experience next-generation urban mobility and parking convenience.',
            'hero_image' => 'images/services/services-hero-clean.png',
            'services_section_title' => 'Our Premier Facilities',
            'services_section_description' => 'Explore the best spots in town.',
            'search_placeholder' => 'Find a specialty...',
            'custom_banner_title' => 'Need Enterprise Fleet Parking?',
            'custom_banner_description' => 'Contact our corporate account team.',
            'custom_banner_button_text' => 'Inquire Today',
            'custom_banner_button_url' => '/corporate-inquiry',
            'custom_banner_icon' => 'bi bi-buildings',
            'seo_title' => 'Exclusive Parking Solutions - SmartParking AI',
            'seo_description' => 'Next-generation parking facility features.',
        ]);

        $response->assertRedirect(route('admin.parking-services.index'));
        $response->assertSessionHas('success');

        // Verify public /services shows the updated CMS content
        $publicRes = $this->get('/services');
        $publicRes->assertSee('SPECIALIZED AMENITIES');
        $publicRes->assertSee('World-Class Parking');
        $publicRes->assertSee('Tailored For You');
        $publicRes->assertSee('Our Premier Facilities');
        $publicRes->assertSee('Need Enterprise Fleet Parking?');
        $publicRes->assertSee('Inquire Today');

        // Restore defaults
        $setting = ServicePageSetting::getSettings();
        $setting->update([
            'hero_eyebrow' => 'OUR SERVICES',
            'hero_title' => 'Parking Services',
            'hero_highlight' => 'For Every Need',
            'hero_description' => 'Choose the right parking solution for a smarter and hassle-free experience.',
            'services_section_title' => 'Our Parking Services',
            'services_section_description' => 'Explore our range of parking solutions designed for your convenience.',
            'search_placeholder' => 'Search services...',
            'custom_banner_title' => 'Need a Custom Parking Solution?',
            'custom_banner_description' => 'Contact us for corporate, event or bulk parking requirements.',
            'custom_banner_button_text' => 'Contact Us',
            'custom_banner_button_url' => '#contact',
        ]);
    }
}
