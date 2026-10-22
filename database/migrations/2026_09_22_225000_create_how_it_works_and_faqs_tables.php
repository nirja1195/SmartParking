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
        // 1. How It Works Page Settings
        if (!Schema::hasTable('how_it_works_page_settings')) {
            Schema::create('how_it_works_page_settings', function (Blueprint $table) {
                $table->id();
                $table->string('hero_eyebrow', 100)->default('HOW IT WORKS');
                $table->string('hero_title', 150)->default('Parking Made');
                $table->string('hero_highlight', 150)->default('Simple');
                $table->text('hero_description')->nullable();
                $table->string('hero_image', 255)->default('images/how-it-works/hero-facility.png');

                $table->string('section_title', 150)->default('How It Works');
                $table->string('section_description', 255)->default('Get your parking spot in 4 simple steps.');

                $table->string('faq_eyebrow', 100)->default('FAQ');
                $table->string('faq_title', 150)->default('Frequently');
                $table->string('faq_highlight', 150)->default('Asked Questions');
                $table->text('faq_description')->nullable();

                $table->string('seo_title', 200)->default('How It Works - SmartParking AI');
                $table->text('seo_description')->nullable();
                $table->string('seo_keywords', 255)->nullable();
                $table->string('og_image', 255)->nullable();

                $table->string('status', 20)->default('Active');
                $table->timestamps();
            });
        }

        // 2. How It Works Steps
        if (!Schema::hasTable('how_it_works_steps')) {
            Schema::create('how_it_works_steps', function (Blueprint $table) {
                $table->id();
                $table->string('step_number', 10)->default('01');
                $table->string('title', 150);
                $table->text('description')->nullable();
                $table->string('image', 255)->nullable();
                $table->string('icon', 100)->default('bi bi-search');
                $table->string('button_text', 100)->nullable();
                $table->string('button_url', 255)->nullable();
                $table->string('status', 20)->default('Active');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 3. How It Works App CTA Banner
        if (!Schema::hasTable('how_it_works_cta')) {
            Schema::create('how_it_works_cta', function (Blueprint $table) {
                $table->id();
                $table->string('eyebrow', 100)->default('SMARTER PARKING');
                $table->string('title', 200)->default('Your Next Parking Spot is Just a Few Taps Away!');
                $table->text('description')->nullable();
                $table->string('main_image', 255)->default('images/how-it-works/cta-phone.png');
                $table->string('google_play_url', 255)->default('#');
                $table->string('app_store_url', 255)->default('#');
                $table->string('status', 20)->default('Active');
                $table->timestamps();
            });
        }

        // 4. How It Works App CTA Features (4 items on the right of banner)
        if (!Schema::hasTable('how_it_works_cta_features')) {
            Schema::create('how_it_works_cta_features', function (Blueprint $table) {
                $table->id();
                $table->string('icon', 100)->default('bi bi-geo-alt-fill');
                $table->string('title', 150);
                $table->string('description', 255)->nullable();
                $table->integer('sort_order')->default(0);
                $table->string('status', 20)->default('Active');
                $table->timestamps();
            });
        }

        // 5. Frequently Asked Questions (FAQs)
        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->string('category', 100)->default('General');
                $table->text('question');
                $table->text('answer');
                $table->string('status', 20)->default('Active');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('how_it_works_cta_features');
        Schema::dropIfExists('how_it_works_cta');
        Schema::dropIfExists('how_it_works_steps');
        Schema::dropIfExists('how_it_works_page_settings');
    }
};
