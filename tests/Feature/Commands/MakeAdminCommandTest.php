<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Enums\Roles;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Seed roles
    foreach (Roles::cases() as $role) {
        Role::query()->firstOrCreate(['name' => $role->value]);
    }
});

test('it can assign super admin role to an existing user', function () {
    $user = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);

    $this->artisan('make:admin')
        ->expectsQuestion('What would you like to do?', 'existing')
        ->expectsQuestion('Search for a user by name', '')
        ->expectsQuestion('Select a user', (string) $user->id)
        ->expectsOutputToContain('User John Doe (john@example.com) has been assigned the Super Admin role.')
        ->assertSuccessful();

    expect($user->fresh()->hasRole(Roles::SuperAdmin->value))->toBeTrue();
});

test('it can create a new user and assign super admin role', function () {
    $email = 'newadmin@example.com';

    $this->artisan('make:admin')
        ->expectsChoice('What would you like to do?', 'new', ['existing' => 'Select an existing user', 'new' => 'Create a new user'])
        ->expectsQuestion('Name', 'New Admin')
        ->expectsQuestion('Email', $email)
        ->expectsQuestion('Password', 'Password123!')
        ->expectsOutputToContain("User {$email} has been created and assigned the Super Admin role.")
        ->assertSuccessful();

    $user = User::query()->where('email', $email)->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('New Admin')
        ->and($user->hasRole(Roles::SuperAdmin->value))->toBeTrue();
});
