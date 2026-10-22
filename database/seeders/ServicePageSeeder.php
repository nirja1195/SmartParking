<?php

namespace Database\Seeders;

use App\Models\ServicePageFeature;
use App\Models\ServicePageSetting;
use Illuminate\Database\Seeder;

class ServicePageSeeder extends Seeder
{
    /**
     * Seed Service page settings and features.
     */
    public function run(): void
    {
        // 1. Settings
        ServicePageSetting::getSettings();

        // 2. Features (4 items matching the reference design)
        $features = [
            [
                'icon' => 'bi bi-shield-check',
                'title' => 'Safe & Secure',
                'description' => '24/7 surveillance',
                'sort_order' => 1,
                'status' => 'Active',
            ],
            [
                'icon' => 'bi bi-geo-alt-fill',
                'title' => 'Multiple Locations',
                'description' => 'Across the city',
                'sort_order' => 2,
                'status' => 'Active',
            ],
            [
                'icon' => 'bi bi-credit-card-2-front-fill',
                'title' => 'Easy Payments',
                'description' => 'Multiple payment options',
                'sort_order' => 3,
                'status' => 'Active',
            ],
            [
                'icon' => 'bi bi-headset',
                'title' => '24/7 Support',
                'description' => "We're always here",
                'sort_order' => 4,
                'status' => 'Active',
            ],
        ];

        foreach ($features as $feat) {
            ServicePageFeature::updateOrCreate(['title' => $feat['title']], $feat);
        }
    }
}
