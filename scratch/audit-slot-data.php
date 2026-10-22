<?php
require __DIR__.'/../vendor/autoload.php';
$app=require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo json_encode(App\Models\ParkingLocation::query()->withCount('slots')->get(['id','name','city'])->toArray(), JSON_PRETTY_PRINT), PHP_EOL;
echo 'Slots: ', App\Models\ParkingSlot::count(), PHP_EOL;
echo 'Slots with no floor or zone floor: ', App\Models\ParkingSlot::whereNull('floor')->whereDoesntHave('zone', fn($q)=>$q->whereNotNull('floor'))->count(), PHP_EOL;