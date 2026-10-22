<?php
namespace Database\Seeders;

use App\Models\Setting;
use App\Support\AboutContent;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(['key' => 'about_page_content'], [
            'value' => json_encode(AboutContent::defaults(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'group' => 'about',
        ]);
    }
}
