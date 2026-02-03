<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Auth\CreateAdminUser;
use App\Enums\Roles;
use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or assign a user to the Super Admin role';

    /**
     * Execute the console command.
     */
    public function handle(CreateAdminUser $createAdminUser): void
    {
        $option = select(
            label: 'What would you like to do?',
            options: [
                'existing' => 'Select an existing user',
                'new' => 'Create a new user',
            ],
            default: 'existing'
        );

        if ($option === 'existing') {
            $user = $this->selectUser();
            if ($user) {
                $user->assignRole(Roles::SuperAdmin->value);
                info("User {$user->name} ({$user->email}) has been assigned the Super Admin role.");
            }
        } else {
            $name = text(label: 'Name', required: true);
            $email = text(
                label: 'Email',
                required: true,
                validate: fn (string $value) => User::query()->where('email', $value)->exists()
                    ? 'Email already exists'
                    : null
            );
            $pass = password(label: 'Password', required: true);

            $user = $createAdminUser->execute([
                'name' => $name,
                'email' => $email,
                'password' => $pass,
            ]);

            info("User {$user->email} has been created and assigned the Super Admin role.");
        }
    }

    private function selectUser(): ?User
    {
        $value = text(label: 'Search for a user by name', placeholder: 'Enter name...');

        $users = User::query()
            ->when(filled($value), fn ($q) => $q->where('name', 'like', "%{$value}%"))
            ->limit(10)
            ->get()
            ->mapWithKeys(fn (User $user) => [$user->id => "{$user->name} ({$user->email})"])
            ->all();

        if (blank($users)) {
            info('No users found.');

            return null;
        }

        $userId = select(
            label: 'Select a user',
            options: $users + [0 => 'Cancel'],
            default: 0
        );

        if ($userId === 0) {
            return null;
        }

        return User::query()->find($userId);
    }
}
