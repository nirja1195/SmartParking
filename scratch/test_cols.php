<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "PARKING LOCATIONS COLUMNS:\n";
print_r(Illuminate\Support\Facades\Schema::getColumnListing('parking_locations'));

echo "\nSAMPLE RECORD:\n";
print_r(App\Models\ParkingLocation::first()?->toArray());
