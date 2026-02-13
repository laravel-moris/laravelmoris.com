<?php

declare(strict_types=1);

use App\Actions\User\CreateUser;
use App\Actions\User\UpdateUser;
use App\Data\User\CreateUserData;
use App\Data\User\UpdateUserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseHas;

it('creates a user without avatar', function () {
    Storage::fake('public');

    $data = new CreateUserData(
        name: 'Test User',
        email: 'test@example.com',
        password: 'password123',
        title: 'Developer',
        bio: 'A test user',
        avatar: null,
    );

    $user = app(CreateUser::class)->execute($data);

    expect($user)->toBeInstanceOf(User::class)->and($user->name)->toBe('Test User')->and($user->email)->toBe('test@example.com')->and($user->title)->toBe('Developer')->and($user->bio)->toBe('A test user')->and($user->password)->not->toBeNull();

    assertDatabaseHas(User::class, [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'title' => 'Developer',
        'bio' => 'A test user',
    ]);
});

it('creates a user with avatar', function () {
    Storage::fake('public');

    $avatar = UploadedFile::fake()->image('avatar.png', 100, 100);

    $data = new CreateUserData(
        name: 'User With Avatar',
        email: 'avatar@example.com',
        password: 'password123',
        avatar: $avatar,
    );

    $user = app(CreateUser::class)->execute($data);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->getFirstMediaUrl('avatar'))
        ->not->toBeEmpty();
});

it('creates a user without optional fields', function () {
    Storage::fake('public');

    $data = new CreateUserData(
        name: 'Minimal User',
        email: 'minimal@example.com',
        password: 'password123',
    );

    $user = app(CreateUser::class)->execute($data);

    expect($user)->toBeInstanceOf(User::class)->and($user->title)->toBeNull()->and($user->bio)->toBeNull();
});

it('updates a user without avatar', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
        'title' => 'Original Title',
        'bio' => 'Original bio',
    ]);

    $data = new UpdateUserData(
        name: 'Updated Name',
        email: 'updated@example.com',
        password: 'newpassword',
        title: 'Updated Title',
        bio: 'Updated bio',
    );

    $updatedUser = app(UpdateUser::class)->execute($user, $data);

    expect($updatedUser->name)->toBe('Updated Name')->and($updatedUser->email)->toBe('updated@example.com')->and($updatedUser->title)->toBe('Updated Title')->and($updatedUser->bio)->toBe('Updated bio');

    assertDatabaseHas(User::class, [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('updates a user with new avatar', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'User With Avatar',
        'email' => 'avatar@example.com',
    ]);

    $newAvatar = UploadedFile::fake()->image('new-avatar.png', 200, 200);

    $data = new UpdateUserData(
        name: 'Updated With Avatar',
        email: 'updated-avatar@example.com',
        avatar: $newAvatar,
    );

    $updatedUser = app(UpdateUser::class)->execute($user, $data);

    expect($updatedUser->name)->toBe('Updated With Avatar')->and($updatedUser->getFirstMediaUrl('avatar'))->not->toBeEmpty();
});

it('deletes avatar when deleteAvatar is true', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'User With Avatar',
        'email' => 'avatar-delete@example.com',
    ]);

    // Add avatar
    $avatar = UploadedFile::fake()->image('old-avatar.png', 100, 100);
    $user->addMedia($avatar)->toMediaCollection('avatar');

    expect($user->getFirstMediaUrl('avatar'))->not->toBeEmpty();

    // Delete avatar
    $data = new UpdateUserData(
        name: 'User Without Avatar',
        email: 'no-avatar@example.com',
        deleteAvatar: true,
    );

    $updatedUser = app(UpdateUser::class)->execute($user, $data);

    expect($updatedUser->name)->toBe('User Without Avatar')->and($updatedUser->getFirstMediaUrl('avatar'))->toBeEmpty();
});

it('replaces existing avatar with new one', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'User Replacing Avatar',
        'email' => 'replace-avatar@example.com',
    ]);

    // Add initial avatar
    $oldAvatar = UploadedFile::fake()->image('old-avatar.png', 100, 100);
    $user->addMedia($oldAvatar)->toMediaCollection('avatar');

    $oldAvatarUrl = $user->getFirstMediaUrl('avatar');
    expect($oldAvatarUrl)->not->toBeEmpty();

    // Replace with new avatar
    $newAvatar = UploadedFile::fake()->image('new-avatar.png', 200, 200);

    $data = new UpdateUserData(
        name: 'User Replacing Avatar',
        email: 'replace-avatar@example.com',
        avatar: $newAvatar,
    );

    $updatedUser = app(UpdateUser::class)->execute($user, $data);

    expect($updatedUser->getFirstMediaUrl('avatar'))->not->toBe($oldAvatarUrl);
});

it('updates user without changing password if null', function () {
    $user = User::factory()->create([
        'name' => 'User',
        'email' => 'user@example.com',
        'password' => bcrypt('old-password'),
    ]);

    $data = new UpdateUserData(
        name: 'Updated User',
        email: 'updated@example.com',
        password: null,
    );

    $updatedUser = app(UpdateUser::class)->execute($user, $data);

    expect($updatedUser->name)->toBe('Updated User')->and($updatedUser->email)->toBe('updated@example.com');

    // Password should remain unchanged
    expect(Hash::check('old-password', $updatedUser->password))->toBeTrue();
});
