<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePageSetting extends Model
{
    protected $fillable = [
        'hero_eyebrow',
        'hero_title',
        'hero_highlight',
        'hero_description',
        'hero_image',
        'services_section_title',
        'services_section_description',
        'search_placeholder',
        'custom_banner_title',
        'custom_banner_description',
        'custom_banner_button_text',
        'custom_banner_button_url',
        'custom_banner_icon',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'status',
    ];

    /**
     * Retrieve the active setting record or initialize default.
     */
    public static function getSettings(): self
    {
        return static::firstOrCreate([], [
            'hero_eyebrow' => 'OUR SERVICES',
            'hero_title' => 'Parking Services',
            'hero_highlight' => 'For Every Need',
            'hero_description' => 'Choose the right parking solution for a smarter and hassle-free experience.',
            'hero_image' => 'images/services/services-hero-clean.png',
            'services_section_title' => 'Our Parking Services',
            'services_section_description' => 'Explore our range of parking solutions designed for your convenience.',
            'search_placeholder' => 'Search services...',
            'custom_banner_title' => 'Need a Custom Parking Solution?',
            'custom_banner_description' => 'Contact us for corporate, event or bulk parking requirements.',
            'custom_banner_button_text' => 'Contact Us',
            'custom_banner_button_url' => '#contact',
            'custom_banner_icon' => 'bi bi-building',
            'seo_title' => 'Parking Services - SmartParking AI',
            'seo_description' => 'Explore our full suite of smart parking solutions including car parking, bike bays, EV charging, accessible spots, and premium parking.',
            'seo_keywords' => 'parking services, EV charging, accessible parking, premium parking, smart park',
            'og_image' => 'images/services/services-hero-clean.png',
            'status' => 'Active',
        ]);
    }
}
