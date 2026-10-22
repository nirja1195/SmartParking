<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Faq;
use App\Models\HowItWorksCta;
use App\Models\HowItWorksCtaFeature;
use App\Models\HowItWorksPageSetting;
use App\Models\HowItWorksStep;
use Tests\TestCase;

class HowItWorksPageTest extends TestCase
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

        // Ensure database is seeded with How It Works default data
        if (HowItWorksStep::count() < 4) {
            $this->seed(\Database\Seeders\HowItWorksSeeder::class);
        }
    }

    /**
     * Test public /how-it-works page loads with 200 status and does not require authentication.
     */
    public function test_how_it_works_page_is_public_and_loads_successfully(): void
    {
        $response = $this->get('/how-it-works');
        $response->assertStatus(200);

        // Header and Active Underline
        $response->assertSee('How It Works');
        $response->assertSee('nav-link-custom active', false);

        // Hero Section
        $response->assertSee('How It Works');

        // Process Steps
        $response->assertSee('Find a Location');
        $response->assertSee('Book Your Slot');
        $response->assertSee('Get Confirmation');
        $response->assertSee('Park & Enjoy');
        $response->assertSee('01');
        $response->assertSee('02');
        $response->assertSee('03');
        $response->assertSee('04');

        // App CTA Banner
        $response->assertSee('SMARTER PARKING');
        $response->assertSee('Your Next Parking Spot is Just a Few Taps Away!');
        $response->assertSee('Find Nearby Parking');
        $response->assertSee('Real-Time Availability');
        $response->assertSee('Secure Payments');
        $response->assertSee('Easy & Fast Booking');

        // FAQ Section
        $response->assertSee('FAQ');

        // Dark Footer
        $response->assertSee('Smart');
        $response->assertSee('Parking');
        $response->assertSee('A Smarter Way to Park');
        $response->assertSee('All rights reserved.');

        // In-page Auth Modal is embedded
        $response->assertSee('openAuthModal', false);
    }

    /**
     * Test guest is redirected when trying to access admin CMS.
     */
    public function test_guest_cannot_access_admin_how_it_works_cms(): void
    {
        $response = $this->get('/admin/how-it-works');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test admin can access How It Works CMS panel.
     */
    public function test_admin_can_access_how_it_works_cms(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/how-it-works');
        $response->assertStatus(200);
        $response->assertSee('How It Works CMS');
        $response->assertSee('1. Steps Process');
    }

    /**
     * Test admin can update page settings dynamically.
     */
    public function test_admin_can_update_page_settings(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post('/admin/how-it-works/settings', [
                'hero_eyebrow' => 'EASY 4-STEP PARKING',
                'hero_title' => 'Parking Is Now',
                'hero_highlight' => 'Effortless',
                'hero_description' => 'Tested dynamic description update.',
                'section_title' => 'Simple Steps',
                'section_description' => 'Fast and easy process.',
                'faq_eyebrow' => 'HELP & FAQ',
                'faq_title' => 'Common',
                'faq_highlight' => 'Inquiries',
                'faq_description' => 'Find quick answers to common questions.',
                'seo_title' => 'How SmartParking AI Works',
                'status' => 'Active',
            ]);

        $response->assertRedirect('/admin/how-it-works?tab=settings');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('how_it_works_page_settings', [
            'hero_eyebrow' => 'EASY 4-STEP PARKING',
            'hero_highlight' => 'Effortless',
        ]);

        // Verify public page reflects the dynamic changes
        $public = $this->get('/how-it-works');
        $public->assertSee('EASY 4-STEP PARKING');
        $public->assertSee('Effortless');
    }

    /**
     * Test admin can create, update, and delete a step.
     */
    public function test_admin_step_crud(): void
    {
        // 1. Create Step
        $createResponse = $this->actingAs($this->admin, 'admin')
            ->post('/admin/how-it-works/steps', [
                'step_number' => '05',
                'title' => 'Automated Valet Pickup',
                'description' => 'Request your car right from your smartphone.',
                'icon' => 'bi-robot',
                'image' => 'images/how-it-works/step-01.png',
                'sort_order' => 5,
                'status' => 'Active',
            ]);

        $createResponse->assertRedirect('/admin/how-it-works?tab=steps');
        $this->assertDatabaseHas('how_it_works_steps', ['title' => 'Automated Valet Pickup']);

        $step = HowItWorksStep::where('title', 'Automated Valet Pickup')->first();

        // 2. Update Step
        $updateResponse = $this->actingAs($this->admin, 'admin')
            ->put("/admin/how-it-works/steps/{$step->id}", [
                'step_number' => '05',
                'title' => 'Automated Valet Pickup Pro',
                'description' => 'Updated step description for testing.',
                'icon' => 'bi-robot',
                'image' => 'images/how-it-works/step-01.png',
                'sort_order' => 5,
                'status' => 'Active',
            ]);

        $updateResponse->assertRedirect('/admin/how-it-works?tab=steps');
        $this->assertDatabaseHas('how_it_works_steps', ['title' => 'Automated Valet Pickup Pro']);

        // 3. Delete Step
        $deleteResponse = $this->actingAs($this->admin, 'admin')
            ->delete("/admin/how-it-works/steps/{$step->id}");

        $deleteResponse->assertRedirect('/admin/how-it-works?tab=steps');
        $this->assertDatabaseMissing('how_it_works_steps', ['id' => $step->id]);
    }

    /**
     * Test admin can create and manage FAQs dynamically.
     */
    public function test_admin_faq_crud(): void
    {
        // 1. Create FAQ
        $createResponse = $this->actingAs($this->admin, 'admin')
            ->post('/admin/how-it-works/faqs', [
                'category' => 'Billing',
                'question' => 'How do I download my VAT invoice?',
                'answer' => 'VAT invoices are sent to your registered email immediately upon payment.',
                'sort_order' => 10,
                'status' => 'Active',
            ]);

        $createResponse->assertRedirect('/admin/how-it-works?tab=faqs');
        $this->assertDatabaseHas('faqs', ['question' => 'How do I download my VAT invoice?']);

        $faq = Faq::where('question', 'How do I download my VAT invoice?')->first();

        // 2. Update FAQ
        $updateResponse = $this->actingAs($this->admin, 'admin')
            ->put("/admin/how-it-works/faqs/{$faq->id}", [
                'category' => 'Billing & Invoicing',
                'question' => 'How do I download my tax invoice?',
                'answer' => 'Tax invoices can be downloaded directly from your booking receipt screen.',
                'sort_order' => 10,
                'status' => 'Active',
            ]);

        $updateResponse->assertRedirect('/admin/how-it-works?tab=faqs');
        $this->assertDatabaseHas('faqs', ['question' => 'How do I download my tax invoice?']);

        // 3. Delete FAQ
        $deleteResponse = $this->actingAs($this->admin, 'admin')
            ->delete("/admin/how-it-works/faqs/{$faq->id}");

        $deleteResponse->assertRedirect('/admin/how-it-works?tab=faqs');
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }
}
