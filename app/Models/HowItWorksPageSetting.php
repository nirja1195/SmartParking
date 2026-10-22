<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowItWorksPageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_eyebrow',
        'hero_title',
        'hero_highlight',
        'hero_description',
        'hero_image',
        'section_title',
        'section_description',
        'faq_eyebrow',
        'faq_title',
        'faq_highlight',
        'faq_description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'status',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
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
        ]);
    }
}
