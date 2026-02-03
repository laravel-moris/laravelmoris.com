<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
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
