<?php

declare(strict_types=1);

use App\Actions\Profile\StoreAvatar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('it stores valid jpg image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100)->size(100);
    $content = file_get_contents($file->getPathname());

    $result = app(StoreAvatar::class)->execute($content, 'jpg');

    expect($result)->not->toBeNull();
    Storage::disk('public')->assertExists($result);
});

test('it stores valid png image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.png', 100, 100)->size(100);
    $content = file_get_contents($file->getPathname());

    $result = app(StoreAvatar::class)->execute($content, 'png');

    expect($result)->not->toBeNull();
    Storage::disk('public')->assertExists($result);
});

test('it stores valid jpeg image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpeg', 100, 100)->size(100);
    $content = file_get_contents($file->getPathname());

    $result = app(StoreAvatar::class)->execute($content, 'jpeg');

    expect($result)->not->toBeNull();
    Storage::disk('public')->assertExists($result);
});

test('it rejects invalid extension', function () {
    Storage::fake('public');

    $result = app(StoreAvatar::class)->execute('fake content', 'gif');

    expect($result)->toBeNull();
});

test('it rejects null extension', function () {
    Storage::fake('public');

    $result = app(StoreAvatar::class)->execute('fake content', null);

    expect($result)->toBeNull();
});

test('it rejects empty content', function () {
    Storage::fake('public');

    $result = app(StoreAvatar::class)->execute('', 'jpg');

    expect($result)->toBeNull();
});

test('it rejects null content', function () {
    Storage::fake('public');

    $result = app(StoreAvatar::class)->execute(null, 'jpg');

    expect($result)->toBeNull();
});

test('it generates unique filename', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);
    $content = file_get_contents($file->getPathname());

    $result1 = app(StoreAvatar::class)->execute($content, 'jpg');
    $result2 = app(StoreAvatar::class)->execute($content, 'jpg');

    expect($result1)->not->toBe($result2);
});
