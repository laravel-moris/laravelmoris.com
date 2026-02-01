<?php

declare(strict_types=1);

use App\Support\WebP;

test('it converts image to webp', function () {
    $image = imagecreatetruecolor(100, 100);
    ob_start();
    imagepng($image);
    $content = (string) ob_get_clean();
    imagedestroy($image);

    $webp = WebP::make($content)->toBinary();

    $info = getimagesizefromstring($webp);
    expect($info['mime'])->toBe('image/webp');
});

test('it resizes image maintaining aspect ratio', function () {
    $image = imagecreatetruecolor(800, 400);
    ob_start();
    imagepng($image);
    $content = (string) ob_get_clean();
    imagedestroy($image);

    $webp = WebP::make($content)
        ->maxWidth(400)
        ->toBinary();

    $info = getimagesizefromstring($webp);
    expect($info[0])->toBe(400)
        ->and($info[1])->toBe(200);
});

test('it preserves transparency', function () {
    $image = imagecreatetruecolor(100, 100);
    imagealphablending($image, false);
    imagesavealpha($image, true);
    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
    imagefill($image, 0, 0, $transparent);

    ob_start();
    imagepng($image);
    $content = (string) ob_get_clean();
    imagedestroy($image);

    $webp = WebP::make($content)->toBinary();

    $resultImage = imagecreatefromstring($webp);
    $colorIndex = imagecolorat($resultImage, 50, 50);
    $colorInfo = imagecolorsforindex($resultImage, $colorIndex);

    expect($colorInfo['alpha'])->toBe(127);
    imagedestroy($resultImage);
});
