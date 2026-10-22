<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowItWorksCta extends Model
{
    use HasFactory;

    protected $table = 'how_it_works_cta';

    protected $fillable = [
        'eyebrow',
        'title',
        'description',
        'main_image',
        'google_play_url',
        'app_store_url',
        'status',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'eyebrow' => 'SMARTER PARKING',
            'title' => 'Your Next Parking Spot is Just a Few Taps Away!',
            'description' => 'Download the SmartParking app to easily search, reserve, and navigate to parking spaces anytime, anywhere. Experience seamless contactless parking today.',
            'main_image' => 'images/how-it-works/cta-phone.png',
            'google_play_url' => 'https://play.google.com',
            'app_store_url' => 'https://apps.apple.com',
            'status' => 'Active',
        ]);
    }
}
