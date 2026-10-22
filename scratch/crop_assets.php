<?php

$srcPath = 'C:/Users/User/.gemini/antigravity/brain/e4e5adee-6ae1-4776-a64a-7b355f2d94d2/.user_uploaded/media_1790019590169.png';
$src = imagecreatefrompng($srcPath);

$targetDir = __DIR__ . '/../public/images/landing';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Full reference copy as backup
copy($srcPath, $targetDir . '/reference.png');

// 1. Hero Parking Image (right side of hero)
// Around x: 230 to 512, y: 55 to 265
$heroCrop = imagecrop($src, ['x' => 228, 'y' => 55, 'width' => 284, 'height' => 210]);
if ($heroCrop) {
    imagepng($heroCrop, $targetDir . '/hero-parking.png');
    imagedestroy($heroCrop);
    echo "Saved hero-parking.png\n";
}

// 2. Services section: 5 cards between y ~390 to ~445
// Total width ~470, card width ~85-90 each
$servicesY = 390;
$servicesH = 55;

$crops = [
    'service-car.png' => ['x' => 22, 'y' => 390, 'width' => 90, 'height' => 54],
    'service-bike.png' => ['x' => 119, 'y' => 390, 'width' => 90, 'height' => 54],
    'service-ev.png' => ['x' => 215, 'y' => 390, 'width' => 90, 'height' => 54],
    'service-accessible.png' => ['x' => 311, 'y' => 390, 'width' => 90, 'height' => 54],
    'service-hourly.png' => ['x' => 407, 'y' => 390, 'width' => 90, 'height' => 54],
];

foreach ($crops as $filename => $coords) {
    $c = imagecrop($src, $coords);
    if ($c) {
        imagepng($c, $targetDir . '/' . $filename);
        imagedestroy($c);
        echo "Saved {$filename}\n";
    }
}

// 3. Banner Car: center of bottom banner y: 625 to 705, x: 230 to 395
$bannerCrop = imagecrop($src, ['x' => 230, 'y' => 625, 'width' => 170, 'height' => 80]);
if ($bannerCrop) {
    imagepng($bannerCrop, $targetDir . '/banner-car.png');
    imagedestroy($bannerCrop);
    echo "Saved banner-car.png\n";
}

imagedestroy($src);
echo "All assets extracted successfully.\n";
