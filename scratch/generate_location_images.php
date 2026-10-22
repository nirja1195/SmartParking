<?php

$dir = __DIR__ . '/../public/images/locations';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$images = [
    'city-center.jpg' => ['#1E293B', '#F9B91E', 'City Center Mall'],
    'riverview-mall.jpg' => ['#0F172A', '#38BDF8', 'Riverview Mall'],
    'airport.jpg' => ['#312E81', '#C084FC', 'Airport Parking'],
    'business-park.jpg' => ['#1C1917', '#FB923C', 'Business Park'],
    'metro-station.jpg' => ['#064E3B', '#4ADE80', 'Metro Station'],
    'hospital.jpg' => ['#831843', '#F472B6', 'City Care Hospital'],
    'university.jpg' => ['#78350F', '#FACC15', 'University Area'],
    'law-garden.jpg' => ['#1E3A8A', '#60A5FA', 'Law Garden'],
    'sidebar-parking.jpg' => ['#0A0F1D', '#F9B91E', 'PARK SAFE DRIVE HAPPY'],
];

foreach ($images as $filename => [$bgHex, $accentHex, $text]) {
    $filePath = $dir . '/' . $filename;
    
    // Create image
    $width = $filename === 'sidebar-parking.jpg' ? 250 : 120;
    $height = $filename === 'sidebar-parking.jpg' ? 180 : 80;
    $im = imagecreatetruecolor($width, $height);
    
    // Convert hex to rgb
    list($r, $g, $b) = sscanf($bgHex, "#%02x%02x%02x");
    $bgColor = imagecolorallocate($im, $r, $g, $b);
    imagefill($im, 0, 0, $bgColor);
    
    list($ar, $ag, $ab) = sscanf($accentHex, "#%02x%02x%02x");
    $accentColor = imagecolorallocate($im, $ar, $ag, $ab);
    
    // Draw abstract grid/structure lines for realistic parking visual
    for ($i = 0; $i < $width; $i += 20) {
        imageline($im, $i, 0, $i, $height, imagecolorallocatealpha($im, 255, 255, 255, 110));
    }
    for ($j = 0; $j < $height; $j += 20) {
        imageline($im, 0, $j, $width, $j, imagecolorallocatealpha($im, 255, 255, 255, 110));
    }

    // Accent line
    imagefilledrectangle($im, 0, $height - 6, $width, $height, $accentColor);
    
    // Draw Text
    $textColor = imagecolorallocate($im, 255, 255, 255);
    imagestring($im, 3, 10, intval($height / 2) - 8, substr($text, 0, 18), $textColor);
    
    imagejpeg($im, $filePath, 90);
    imagedestroy($im);
}

echo "Generated " . count($images) . " location images successfully!\n";
