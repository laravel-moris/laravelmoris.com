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

    expect($result)->not->toBeNull()->toContain('.png');
});

test('it defaults to jpg when extension not recognized', function () {
    Storage::fake('public');
    Http::fake([
        'https://example.com/photos/avatar' => Http::response(fakeImageContent('jpg'), 200),
    ]);

    $result = app(DownloadAvatar::class)->execute('https://example.com/photos/avatar');

    expect($result)->not->toBeNull()->toContain('.jpg');
});

/**
 * Helper to generate fake image content.
 */
function fakeImageContent(string $extension): string
{
    // Minimal valid image header for the given extension
    return match ($extension) {
        'jpg', 'jpeg' => "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00",
        'png' => "\x89PNG\r\n\x1A\n\x00\x00\x00\rIHDR\x00\x00\x00\x01\x00\x00\x00\x01\x08\x02\x00\x00\x00\x90wS\xde",
        default => 'fake image data',
    };
}
