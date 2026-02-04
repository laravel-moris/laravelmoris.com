<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;

uses()->group('profile');

it('redirects guest to login when accessing profile page', function () {
    get(route('profile.index'))
        ->assertRedirect(route('login'));
});

it('redirects guest to login when accessing profile edit page', function () {
    get(route('profile.edit'))
        ->assertRedirect(route('login'));
});

it('redirects guest to login when updating profile', function () {
    patch(route('profile.update'), [
        'name' => 'Updated Name',
    ])->assertRedirect(route('login'));
});

it('loads profile page for authenticated user', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'title' => 'Developer',
        'bio' => 'A passionate developer',
    ]);

    actingAs($user);

    get(route('profile.index'))
        ->assertSuccessful()
        ->assertViewIs('pages.profile.index')
        ->assertSee('John Doe')
        ->assertSee('Developer')
        ->assertSee('A passionate developer');
});

it('loads profile edit page for authenticated user', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'title' => 'Developer',
        'bio' => 'A passionate developer',
    ]);

    actingAs($user);

    get(route('profile.edit'))
        ->assertSuccessful()
        ->assertViewIs('pages.profile.edit')
        ->assertSee('Edit Profile')
        ->assertSee('John Doe')
        ->assertSee('Developer')
        ->assertSee('A passionate developer');
});

it('updates profile successfully', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'title' => null,
        'bio' => null,
    ]);

    actingAs($user);

    patch(route('profile.update'), [
        'name' => 'New Name',
        'title' => 'Senior Developer',
        'bio' => 'Updated bio',
    ])
        ->assertRedirect(route('profile.index'))
        ->assertSessionHas('success', 'Profile updated successfully.');

    $user->refresh();

    expect($user->name)->toBe('New Name')
        ->and($user->title)->toBe('Senior Developer')
        ->and($user->bio)->toBe('Updated bio');
});

it('validates required name field', function () {
    $user = User::factory()->create();

    actingAs($user);

    patch(route('profile.update'), [
        'name' => '',
    ])
        ->assertSessionHasErrors(['name']);
});

it('validates name max length', function () {
    $user = User::factory()->create();

    actingAs($user);

    patch(route('profile.update'), [
        'name' => str_repeat('a', 256),
    ])
        ->assertSessionHasErrors(['name']);
});

it('validates title max length', function () {
    $user = User::factory()->create();

    actingAs($user);

    patch(route('profile.update'), [
        'name' => 'Valid Name',
        'title' => str_repeat('a', 256),
    ])
        ->assertSessionHasErrors(['title']);
});

it('validates bio max length', function () {
    $user = User::factory()->create();

    actingAs($user);

    patch(route('profile.update'), [
        'name' => 'Valid Name',
        'bio' => str_repeat('a', 1001),
    ])
        ->assertSessionHasErrors(['bio']);
});

it('allows nullable title and bio fields', function () {
    $user = User::factory()->create([
        'title' => 'Old Title',
        'bio' => 'Old Bio',
    ]);

    actingAs($user);

    patch(route('profile.update'), [
        'name' => 'New Name',
        'title' => null,
        'bio' => null,
    ])
        ->assertRedirect(route('profile.index'));

    $user->refresh();

    expect($user->title)->toBeNull()
        ->and($user->bio)->toBeNull();
});

it('preserves existing avatar when no file uploaded', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
    ]);

    // Add an avatar to media library
    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);
    $user->addMedia($file)->toMediaCollection('avatar');

    actingAs($user);

    patch(route('profile.update'), [
        'name' => 'New Name',
    ])
        ->assertRedirect(route('profile.index'));

    $user->refresh();

    // Avatar should still be in media library
    expect($user->getFirstMedia('avatar'))->not->toBeNull();
});
