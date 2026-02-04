<?php

declare(strict_types=1);

use App\Actions\Profile\UpdateProfile;
use App\Data\Profile\UpdateProfileData;
use App\Models\User;
use Illuminate\Http\UploadedFile;

test('it updates user profile without avatar', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'title' => 'Old Title',
        'bio' => 'Old Bio',
    ]);

    $data = new UpdateProfileData(
        name: 'New Name',
        title: 'New Title',
        bio: 'New Bio',
        avatar: null,
    );

    $result = app(UpdateProfile::class)->execute($user, $data);

    expect($result->name)->toBe('New Name')
        ->and($result->title)->toBe('New Title')
        ->and($result->bio)->toBe('New Bio');
});

test('it updates user profile with avatar', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'title' => null,
        'bio' => null,
    ]);

    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);
    $data = new UpdateProfileData(
        name: 'New Name',
        title: 'Developer',
        bio: 'Bio',
        avatar: $file,
    );

    $result = app(UpdateProfile::class)->execute($user, $data);

    expect($result->name)->toBe('New Name')
        ->and($result->title)->toBe('Developer')
        ->and($result->bio)->toBe('Bio')
        ->and($user->refresh()->getFirstMedia('avatar'))->not->toBeNull();
});

test('it updates nullable fields', function () {
    $user = User::factory()->create([
        'name' => 'Name',
        'title' => 'Old Title',
        'bio' => 'Old Bio',
    ]);

    $data = new UpdateProfileData(
        name: 'Name',
        title: null,
        bio: null,
        avatar: null,
    );

    $result = app(UpdateProfile::class)->execute($user, $data);

    expect($result->title)->toBeNull()
        ->and($result->bio)->toBeNull();
});
