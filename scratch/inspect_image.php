<?php

$imagePath = 'C:/Users/User/.gemini/antigravity/brain/e4e5adee-6ae1-4776-a64a-7b355f2d94d2/.user_uploaded/media_1790019590169.png';
$size = getimagesize($imagePath);
echo "Image dimensions: {$size[0]} x {$size[1]}\n";
