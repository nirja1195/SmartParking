<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\HowItWorksCta;
use App\Models\HowItWorksCtaFeature;
use App\Models\HowItWorksPageSetting;
use App\Models\HowItWorksStep;
use Illuminate\Database\Seeder;

class HowItWorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Page Settings
        HowItWorksPageSetting::updateOrCreate(
            ['id' => 1],
            [
                'hero_eyebrow' => 'HOW IT WORKS',
                'hero_title' => 'Parking Made',
                'hero_highlight' => 'Simple',
                'hero_description' => 'Booking your parking space has never been easier. Follow our simple four-step process to secure your spot in seconds.',
                'hero_image' => 'images/how-it-works/hero-facility.png',
                'section_title' => 'How It Works',
                'section_description' => 'Get your parking spot in 4 simple steps.',
                'faq_eyebrow' => 'FAQ',
                'faq_title' => 'Frequently',
                'faq_highlight' => 'Asked Questions',
                'faq_description' => 'Got questions about how SmartParking works? Find clear answers below or contact our 24/7 support team.',
                'seo_title' => 'How It Works - SmartParking AI',
                'seo_description' => 'Discover how easy parking is with SmartParking AI. 4 simple steps to reserve your spot.',
                'status' => 'Active',
            ]
        );

        // 2. How It Works Steps
        $steps = [
            [
                'step_number' => '01',
                'title' => 'Find a Location',
                'description' => 'Search for parking spaces near your destination and compare prices and amenities.',
                'icon' => 'bi-search',
                'image' => 'images/how-it-works/step-01.png',
                'status' => 'Active',
                'sort_order' => 1,
            ],
            [
                'step_number' => '02',
                'title' => 'Book Your Slot',
                'description' => 'Choose your date, time and parking slot that best fits your schedule and vehicle.',
                'icon' => 'bi-calendar3',
                'image' => 'images/how-it-works/step-02.png',
                'status' => 'Active',
                'sort_order' => 2,
            ],
            [
                'step_number' => '03',
                'title' => 'Get Confirmation',
                'description' => 'Receive instant confirmation with a QR code and detailed navigation directions.',
                'icon' => 'bi-qr-code-scan',
                'image' => 'images/how-it-works/step-03.png',
                'status' => 'Active',
                'sort_order' => 3,
            ],
            [
                'step_number' => '04',
                'title' => 'Park & Enjoy',
                'description' => 'Scan, enter and park with ease. Enjoy a completely hassle-free parking experience.',
                'icon' => 'bi-car-front-fill',
                'image' => 'images/how-it-works/step-04.png',
                'status' => 'Active',
                'sort_order' => 4,
            ],
        ];

        foreach ($steps as $stepData) {
            HowItWorksStep::updateOrCreate(
                ['step_number' => $stepData['step_number']],
                $stepData
            );
        }

        // 3. App CTA Banner
        HowItWorksCta::updateOrCreate(
            ['id' => 1],
            [
                'eyebrow' => 'SMARTER PARKING',
                'title' => 'Your Next Parking Spot is Just a Few Taps Away!',
                'description' => 'Download the SmartParking app to easily search, reserve, and navigate to parking spaces anytime, anywhere. Experience seamless contactless parking today.',
                'main_image' => 'images/how-it-works/cta-phone.png',
                'google_play_url' => 'https://play.google.com',
                'app_store_url' => 'https://apps.apple.com',
                'status' => 'Active',
            ]
        );

        // 4. CTA Features
        $features = [
            [
                'icon' => 'bi-geo-alt-fill',
                'title' => 'Find Nearby Parking',
                'description' => 'Instantly locate available parking spots around your current or target location.',
                'sort_order' => 1,
                'status' => 'Active',
            ],
            [
                'icon' => 'bi-clock-history',
                'title' => 'Real-Time Availability',
                'description' => 'Live slot tracking and instant occupancy updates powered by smart sensors.',
                'sort_order' => 2,
                'status' => 'Active',
            ],
            [
                'icon' => 'bi-shield-check',
                'title' => 'Secure Payments',
                'description' => 'Multiple contactless payment options with encrypted digital transactions.',
                'sort_order' => 3,
                'status' => 'Active',
            ],
            [
                'icon' => 'bi-lightning-charge-fill',
                'title' => 'Easy & Fast Booking',
                'description' => 'Reserve and confirm your ideal spot within seconds from your smartphone.',
                'sort_order' => 4,
                'status' => 'Active',
            ],
        ];

        foreach ($features as $f) {
            HowItWorksCtaFeature::updateOrCreate(
                ['title' => $f['title']],
                $f
            );
        }

        // 5. FAQs
        $faqs = [
            [
                'category' => 'Booking',
                'question' => 'How do I book a parking spot in advance?',
                'answer' => 'Simply search your desired destination or parking facility, select your vehicle type, choose arrival date and duration, and confirm your booking with a secure payment. You will receive an instant digital pass with a QR code.',
                'status' => 'Active',
                'sort_order' => 1,
            ],
            [
                'category' => 'Booking',
                'question' => 'Can I cancel or modify my reservation?',
                'answer' => 'Yes, you can cancel or reschedule your reservation up to 1 hour before your scheduled arrival time directly from your dashboard or mobile app. Refunds are processed automatically to your original payment method.',
                'status' => 'Active',
                'sort_order' => 2,
            ],
            [
                'category' => 'Access',
                'question' => 'How does the QR code entry work?',
                'answer' => 'When you arrive at the parking facility barrier, hold your digital QR code (from your email or app) up to the optical scanner. The barrier will verify your booking and raise automatically within seconds.',
                'status' => 'Active',
                'sort_order' => 3,
            ],
            [
                'category' => 'Payment',
                'question' => 'What payment methods are supported?',
                'answer' => 'We accept all major credit and debit cards (Visa, MasterCard, American Express), digital wallets (Google Pay, Apple Pay), and net banking. All transactions are 256-bit SSL encrypted.',
                'status' => 'Active',
                'sort_order' => 4,
            ],
            [
                'category' => 'Policy',
                'question' => 'What happens if I exceed my booked parking time?',
                'answer' => 'If your stay exceeds your reserved period, overtime charges will be calculated at standard hourly facility rates and automatically charged to your linked payment method upon exit scan.',
                'status' => 'Active',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
