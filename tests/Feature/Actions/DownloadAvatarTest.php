<?php

declare(strict_types=1);

use App\Actions\Profile\DownloadAvatar;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

test('it downloads and stores avatar from url', function () {
    Storage::fake('public');
    Http::fake([
        'https://example.com/avatar.jpg' => Http::response(fakeImageContent('jpg'), 200),
    ]);

    $result = app(DownloadAvatar::class)->execute('https://example.com/avatar.jpg');

    expect($result)->not->toBeNull();
    Storage::disk('public')->assertExists($result);
});

test('it returns null for empty url', function () {
    $result = app(DownloadAvatar::class)->execute('');

    expect($result)->toBeNull();
});

test('it returns null for null url', function () {
    $result = app(DownloadAvatar::class)->execute(null);

    expect($result)->toBeNull();
});

test('it returns null when request fails', function () {
    Http::fake([
        'https://example.com/avatar.jpg' => Http::response('', 404),
    ]);

    $result = app(DownloadAvatar::class)->execute('https://example.com/avatar.jpg');

    expect($result)->toBeNull();
});

test('it returns null on connection error', function () {
    Http::fake(function () {
        throw new ConnectionException('Connection failed');
    });

    $result = app(DownloadAvatar::class)->execute('https://example.com/avatar.jpg');

    expect($result)->toBeNull();
});

test('it extracts extension from url path', function () {
    Storage::fake('public');
    Http::fake([
        'https://example.com/photos/avatar.png' => Http::response(fakeImageContent('png'), 200),
    ]);

    $result = app(DownloadAvatar::class)->execute('https://example.com/photos/avatar.png');

    expect($result)->not->toBeNull()->toContain('.webp');
});

test('it defaults to jpg when extension not recognized', function () {
    Storage::fake('public');
    Http::fake([
        'https://example.com/photos/avatar' => Http::response(fakeImageContent('jpg'), 200),
    ]);

    $result = app(DownloadAvatar::class)->execute('https://example.com/photos/avatar');

    expect($result)->not->toBeNull()->toContain('.webp');
});

/**
 * Helper to generate fake image content.
 */
function fakeImageContent(string $extension): string
{
    $image = imagecreatetruecolor(10, 10);
    ob_start();
    match ($extension) {
        'png' => imagepng($image),
        default => imagejpeg($image),
    };
    $content = ob_get_clean();
    imagedestroy($image);

    return (string) $content;
}
