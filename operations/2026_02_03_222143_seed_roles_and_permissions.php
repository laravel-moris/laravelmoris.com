<?php

declare(strict_types=1);

use App\Enums\Permissions;
use App\Enums\Roles;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation
{
    /**
     * Determine if the operation is being processed asynchronously.
     */
    protected bool $async = false;

    /**
     * Process the operation.
     */
    public function process(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        foreach (Permissions::cases() as $permission) {
            Permission::firstOrCreate(['name' => $permission->value]);
        }

        // Create Roles
        foreach (Roles::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value]);
        }

        // Assign Permissions to Roles
        $superAdmin = Role::findByName(Roles::SuperAdmin->value);
        $superAdmin->givePermissionTo(Permissions::AccessAdminPanel->value);
    }
};
