<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin;
use App\Models\User;
use App\Models\ParkingLocation;
use App\Models\ParkingZone;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ParkingEntry;
use App\Models\ParkingExit;
use Illuminate\Support\Facades\Auth;

echo "=== SMARTPARK AI ADMIN PANEL VERIFICATION SUITE ===\n\n";

$admin = Admin::where('email', 'admin@smartpark.com')->first();
if (!$admin) {
    echo "[-] Admin not found! Seeding...\n";
    exit(1);
}

Auth::guard('admin')->setUser($admin);
echo "[+] Admin authenticated as: {$admin->name} ({$admin->email})\n";

// Test list of admin URLs
$urls = [
    '/admin/dashboard',
    '/admin/parking-locations',
    '/admin/parking-locations/create',
    '/admin/parking-zones',
    '/admin/parking-zones/create',
    '/admin/parking-slots',
    '/admin/availability',
    '/admin/users',
    '/admin/users/create',
    '/admin/vehicles',
    '/admin/staff',
    '/admin/bookings',
    '/admin/bookings?filter=today',
    '/admin/bookings?filter=active',
    '/admin/bookings?filter=history',
    '/admin/entries',
    '/admin/exits',
    '/admin/payments',
    '/admin/transactions',
    '/admin/reports',
    '/admin/reports/bookings',
    '/admin/reports/revenue',
    '/admin/reports/occupancy',
    '/admin/reports/parking',
    '/admin/analytics',
    '/admin/ai-insights',
    '/admin/settings',
    '/admin/profile',
    '/'
];

$failed = 0;
foreach ($urls as $url) {
    $request = Illuminate\Http\Request::create($url, 'GET');
    
    // Maintain admin session
    $request->setLaravelSession(app('session')->driver());
    Auth::guard('admin')->setUser($admin);

    $response = $kernel->handle($request);
    $status = $response->getStatusCode();

    if ($status === 200) {
        echo "  [OK 200] {$url}\n";
    } else {
        echo "  [FAIL {$status}] {$url}\n";
        $failed++;
    }
}

echo "\n--- SUMMARY ---\n";
if ($failed === 0) {
    echo "All " . count($urls) . " Admin Panel endpoints responded with HTTP 200 OK!\n";
} else {
    echo "{$failed} endpoints failed.\n";
}
