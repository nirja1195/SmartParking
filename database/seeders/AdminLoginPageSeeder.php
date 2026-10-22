<?php
namespace Database\Seeders;
use App\Models\Setting;
use App\Support\AdminLoginContent;
use Illuminate\Database\Seeder;
class AdminLoginPageSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(['key' => 'admin_login_content'], ['value' => json_encode(AdminLoginContent::defaults(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), 'group' => 'admin_login']);
    }
}
