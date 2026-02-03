<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class CreateAdminUser
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     *
     * @throws Throwable
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'email_verified_at' => now(),
            ]);

            $user->assignRole(Roles::SuperAdmin->value);

            return $user;
        });
    }
}
