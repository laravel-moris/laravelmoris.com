<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Pages\Dashboard;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('renders the Filament admin login page', function () {
    get('/admin/login')->assertOk();
});

it('redirects guests to the Filament login page', function () {
    get('/admin')->assertRedirect('/admin/login');
});

it('allows an admin user to access the dashboard', function () {
    asAdmin();

    Livewire::test(Dashboard::class)
        ->assertOk();
});

it('forbids non-admin users from accessing the panel', function () {
    asFilamentUser(User::factory()->create());

    get('/admin')->assertForbidden();
});
