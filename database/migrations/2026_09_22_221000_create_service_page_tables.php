<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Service Page Settings
        if (!Schema::hasTable('service_page_settings')) {
            Schema::create('service_page_settings', function (Blueprint $table) {
                $table->id();
                // Hero section
                $table->string('hero_eyebrow', 100)->default('OUR SERVICES');
                $table->string('hero_title', 150)->default('Parking Services');
                $table->string('hero_highlight', 150)->default('For Every Need');
                $table->text('hero_description')->nullable();
                $table->string('hero_image', 255)->default('images/services/services-hero-clean.png');

                // Services grid section
                $table->string('services_section_title', 150)->default('Our Parking Services');
                $table->text('services_section_description')->nullable();
                $table->string('search_placeholder', 100)->default('Search services...');

                // Custom Solution Banner
                $table->string('custom_banner_title', 200)->default('Need a Custom Parking Solution?');
                $table->text('custom_banner_description')->nullable();
                $table->string('custom_banner_button_text', 100)->default('Contact Us');
                $table->string('custom_banner_button_url', 255)->default('#contact');
                $table->string('custom_banner_icon', 100)->default('bi bi-building');

                // SEO Metadata
                $table->string('seo_title', 200)->default('Parking Services - SmartParking AI');
                $table->text('seo_description')->nullable();
                $table->string('seo_keywords', 255)->nullable();
                $table->string('og_image', 255)->nullable();

                $table->string('status', 20)->default('Active');
                $table->timestamps();
            });
        }

        // 2. Service Page Features (4-item horizontal feature strip)
        if (!Schema::hasTable('service_page_features')) {
            Schema::create('service_page_features', function (Blueprint $table) {
                $table->id();
                $table->string('icon', 100)->default('bi bi-shield-check');
                $table->string('title', 100);
                $table->string('description', 200);
                $table->integer('sort_order')->default(0);
                $table->string('status', 20)->default('Active');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_page_features');
        Schema::dropIfExists('service_page_settings');
    }
};
