<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$password = getenv('SMARTPARK_SETUP_PASSWORD');
if (!is_string($password) || strlen($password) < 8) throw new RuntimeException('Provide SMARTPARK_SETUP_PASSWORD with at least 8 characters.');
$admin = App\Models\Admin::firstOrNew(['email' => 'admin@admin.com']);
$admin->name = $admin->name ?: 'Administrator';
$admin->role = $admin->role ?: 'Super Admin';
$admin->status = 'active';
$admin->password = Illuminate\Support\Facades\Hash::make($password);
if (!$admin->username && !App\Models\Admin::where('username', 'admin')->where('email', '!=', 'admin@admin.com')->exists()) $admin->username = 'admin';
$admin->save();
echo 'Requested admin account configured; password hash verified: '.(Illuminate\Support\Facades\Hash::check($password, $admin->fresh()->password) ? 'yes' : 'no').PHP_EOL;
