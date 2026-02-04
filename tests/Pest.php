<?php

declare(strict_types=1);

use Spatie\Permission\PermissionRegistrar;
use App\Enums\Permissions;
use Spatie\Permission\Models\Permission;
use App\Enums\Roles;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function () {
        // Seed permissions and roles for tests
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (Permissions::cases() as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission->value]);
        }

        foreach (Roles::cases() as $role) {
            Role::query()->firstOrCreate(['name' => $role->value]);
        }
    })
    ->in('Feature');

function asAdmin(): User
{
    $user = User::factory()->create([
        'email' => 'admin@example.com',
    ]);

    actingAs($user);

    Filament::setCurrentPanel('admin');
    Filament::bootCurrentPanel();

    return $user;
}

function asFilamentUser(User $user): User
{
    actingAs($user);

    Filament::setCurrentPanel('admin');
    Filament::bootCurrentPanel();

    return $user;
}
