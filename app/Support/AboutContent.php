<?php

namespace App\Support;

use App\Models\Setting;

class AboutContent
{
    public static function defaults(): array
    {
        return [
            'branding' => ['mark' => 'P', 'name' => 'Smart', 'highlight' => 'Parking', 'tagline' => 'Park Smarter. Live Easier.'],
            'seo' => ['title' => 'About Us - SmartParking', 'description' => 'Smarter parking for a better tomorrow. Discover the SmartParking story, mission and vision.'],
            'hero' => ['eyebrow' => 'ABOUT US', 'title' => "Smarter Parking\nfor a Better", 'highlight' => 'Tomorrow', 'description' => "We are on a mission to make parking simple,\nconvenient and stress-free for everyone.\nSmartParking connects drivers with secure\nand reliable parking spaces across the city.", 'image' => 'images/about/hero.png', 'alt' => 'Modern parking garage with yellow pillars', 'badge_title' => 'Easy Parking', 'badge_text' => 'Better Living'],
            'story' => ['eyebrow' => 'OUR STORY', 'title' => 'How It All', 'highlight' => 'Started', 'description' => 'SmartParking was founded with a simple idea — parking should be easy, fast and accessible. We noticed how frustrating it can be to find a safe and reliable parking spot, especially in busy areas. That’s why we built SmartParking — a smart platform that helps you find, book and manage parking spaces in just a few clicks.', 'image' => 'images/about/story.png', 'alt' => 'SmartParking facility entrance', 'badge_title' => 'Smart Solutions', 'badge_text' => 'for Modern Cities'],
            'mission' => ['title' => 'Our', 'highlight' => 'Mission', 'description' => 'To simplify the parking experience by providing safe, reliable and accessible parking solutions for everyone.', 'icon' => 'bullseye'],
            'vision' => ['title' => 'Our', 'highlight' => 'Vision', 'description' => 'To become the leading parking platform in every city, making urban mobility smarter and more convenient.', 'icon' => 'eye-fill'],
            'difference' => ['eyebrow' => 'WHY CHOOSE US', 'title' => 'What Makes Us', 'highlight' => 'Different', 'description' => 'We’re not just about parking — we’re about making your life easier.', 'image' => 'images/about/difference.png', 'alt' => 'Driver using the SmartParking app in a parking garage', 'badge' => "More than\njust parking"],
            'features' => [
                ['title' => 'Secure & Safe', 'description' => 'Your vehicle is always in safe hands.', 'icon' => 'shield-fill-check'],
                ['title' => 'Quick & Easy', 'description' => 'Find and book in just a few taps.', 'icon' => 'lightning-fill'],
                ['title' => 'Wide Coverage', 'description' => 'Multiple locations across the city.', 'icon' => 'geo-alt-fill'],
                ['title' => '24/7 Support', 'description' => 'We’re always here to help.', 'icon' => 'emoji-smile'],
            ],
            'statistics' => [
                ['value' => '10,000+', 'label' => 'Happy Customers', 'icon' => 'people-fill'],
                ['value' => '50+', 'label' => 'Parking Locations', 'icon' => 'geo-alt-fill'],
                ['value' => '25,000+', 'label' => 'Bookings Completed', 'icon' => 'car-front-fill'],
                ['value' => '99%', 'label' => 'Customer Satisfaction', 'icon' => 'shield-fill-check'],
            ],
            'navigation' => [
                ['label' => 'Home', 'url' => '/'], ['label' => 'Services', 'url' => '/services'], ['label' => 'How It Works', 'url' => '/how-it-works'], ['label' => 'Parking Locations', 'url' => '/find-parking'], ['label' => 'About', 'url' => '/about'],
            ],
            'footer' => ['description' => 'SmartParking makes urban living easier with safe, secure and convenient parking solutions.', 'links_title' => 'Quick Links', 'support_title' => 'Support', 'app_title' => 'Download Our App', 'app_description' => 'Book parking on the go.', 'google_url' => 'https://play.google.com/store', 'apple_url' => 'https://www.apple.com/app-store/', 'google_caption' => 'GET IT ON', 'google_label' => 'Google Play', 'apple_caption' => 'Download on the', 'apple_label' => 'App Store', 'copyright' => '© 2024 SmartParking. All rights reserved.', 'tagline' => 'A Smarter Way to Park'],
            'support' => [
                ['label' => 'Help Center', 'url' => '/how-it-works#faq'], ['label' => 'Contact Us', 'url' => 'mailto:support@smartparking.com'], ['label' => 'Terms of Service', 'url' => '/#footer'], ['label' => 'Privacy Policy', 'url' => '/#footer'], ['label' => 'FAQ', 'url' => '/how-it-works#faq'],
            ],
            'socials' => [
                ['label' => 'Facebook', 'icon' => 'facebook', 'url' => 'https://facebook.com'], ['label' => 'Instagram', 'icon' => 'instagram', 'url' => 'https://instagram.com'], ['label' => 'Twitter', 'icon' => 'twitter', 'url' => 'https://twitter.com'], ['label' => 'LinkedIn', 'icon' => 'linkedin', 'url' => 'https://linkedin.com'], ['label' => 'YouTube', 'icon' => 'youtube', 'url' => 'https://youtube.com'],
            ],
        ];
    }

    public static function current(): array
    {
        $stored = json_decode(Setting::get('about_page_content', '{}'), true) ?: [];
        // Lists replace defaults completely, so deleted rows stay deleted.
        return array_replace(self::defaults(), $stored);
    }

    public static function lists(): array
    {
        return ['features', 'statistics', 'navigation', 'support', 'socials'];
    }

    public static function imageFields(): array
    {
        return ['hero.image', 'story.image', 'difference.image'];
    }
}
