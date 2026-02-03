<?php

declare(strict_types=1);

use App\Actions\Profile\StoreAvatar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

afterEach(function () {
    Storage::clearResolvedInstance();
});

test('it stores valid jpg image as webp and preserves original', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100)->size(100);
    $content = file_get_contents($file->getPathname());

    $result = app(StoreAvatar::class)->execute($content, 'jpg');

    expect($result)->not->toBeNull()->toEndWith('.webp');

    $uuid = explode('/', $result)[1];

    Storage::disk('public')->assertExists($result);
    Storage::disk('public')->assertExists("avatars/{$uuid}/original.jpg");
});

test('it stores valid png image as webp and preserves original', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.png', 100, 100)->size(100);
    $content = file_get_contents($file->getPathname());

    $result = app(StoreAvatar::class)->execute($content, 'png');

    expect($result)->not->toBeNull()->toEndWith('.webp');

    $uuid = explode('/', $result)[1];

    Storage::disk('public')->assertExists($result);
    Storage::disk('public')->assertExists("avatars/{$uuid}/original.png");
});

test('it stores valid jpeg image as webp and preserves original', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpeg', 100, 100)->size(100);
    $content = file_get_contents($file->getPathname());

    $result = app(StoreAvatar::class)->execute($content, 'jpeg');

    expect($result)->not->toBeNull()->toEndWith('.webp');

    $uuid = explode('/', $result)[1];

    Storage::disk('public')->assertExists($result);
    Storage::disk('public')->assertExists("avatars/{$uuid}/original.jpeg");
});

test('it stores svg and returns svg path', function () {
    Storage::fake('public');

    $content = '<svg>...</svg>';
    $result = app(StoreAvatar::class)->execute($content, 'svg');

    expect($result)->not->toBeNull()->toEndWith('.svg');

    $uuid = explode('/', $result)[1];

    Storage::disk('public')->assertExists($result);
    Storage::disk('public')->assertExists("avatars/{$uuid}/original.svg");
    Storage::disk('public')->assertMissing("avatars/{$uuid}/avatar.webp");
});

test('it resizes large images', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpg', 800, 800);
    $content = file_get_contents($file->getPathname());

    $result = app(StoreAvatar::class)->execute($content, 'jpg');

    expect($result)->not->toBeNull()->toEndWith('.webp');

    $storedContent = Storage::disk('public')->get($result);
    $image = imagecreatefromstring($storedContent);

    expect(imagesx($image))->toBe(400);
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

test('it generates unique uuid for each upload', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);
    $content = file_get_contents($file->getPathname());

    $result1 = app(StoreAvatar::class)->execute($content, 'jpg');
    $result2 = app(StoreAvatar::class)->execute($content, 'jpg');

    $uuid1 = explode('/', $result1)[1];
    $uuid2 = explode('/', $result2)[1];

    expect($uuid1)->not->toBe($uuid2)->and(Str::isUuid($uuid1))->toBeTrue()->and(Str::isUuid($uuid2))->toBeTrue();
});

test('it returns null when it cannot store the original avatar', function () {
    $content = 'content';

    $disk = \Mockery::mock();
    $disk->shouldReceive('put')
        ->once()
        ->with(\Mockery::on(fn (string $path): bool => str_ends_with($path, '/original.jpg')), $content)
        ->andReturnFalse();

    Storage::shouldReceive('disk')
        ->once()
        ->with('public')
        ->andReturn($disk);

    $result = app(StoreAvatar::class)->execute($content, 'jpg');

    expect($result)->toBeNull();
});

test('it returns null when image conversion fails', function () {
    Storage::fake('public');

    $result = app(StoreAvatar::class)->execute('this is not an image', 'jpg');

    expect($result)->toBeNull();

    $files = Storage::disk('public')->allFiles('avatars');
    expect($files)->toHaveCount(1)
        ->and($files[0])->toEndWith('/original.jpg');
});

test('it returns null when it cannot store the webp avatar', function () {
    $image = imagecreatetruecolor(50, 50);
    ob_start();
    imagepng($image);
    $content = (string) ob_get_clean();
    imagedestroy($image);

    $disk = \Mockery::mock();
    $disk->shouldReceive('put')
        ->once()
        ->with(\Mockery::on(fn (string $path): bool => str_ends_with($path, '/original.png')), $content)
        ->andReturnTrue();
    $disk->shouldReceive('put')
        ->once()
        ->with(\Mockery::on(fn (string $path): bool => str_ends_with($path, '/avatar.webp')), \Mockery::type('string'))
        ->andReturnFalse();

    Storage::shouldReceive('disk')
        ->once()
        ->with('public')
        ->andReturn($disk);

    $result = app(StoreAvatar::class)->execute($content, 'png');

    expect($result)->toBeNull();
});
