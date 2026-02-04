<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use App\Filament\Resources\User\Pages\CreateUser;
use App\Filament\Resources\User\Pages\EditUser;
use App\Filament\Resources\User\Pages\ListUsers;
use App\Filament\Resources\User\Pages\ViewUser;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('lists users with search and sorting', function () {
    asAdmin();

    $olderUser = User::factory()->create([
        'name' => 'Older user',
        'email' => 'older@example.com',
        'created_at' => Date::now()->subDays(2),
    ]);

    $newerUser = User::factory()->create([
        'name' => 'Newer user',
        'email' => 'newer@example.com',
        'created_at' => Date::now()->subDay(),
    ]);

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords([$olderUser, $newerUser])
        ->searchTable('Older user')
        ->assertCanSeeTableRecords([$olderUser])
        ->assertCanNotSeeTableRecords([$newerUser]);

    $sortedAsc = User::query()->oldest()->get();

    Livewire::test(ListUsers::class)
        ->sortTable('created_at', 'asc')
        ->assertCanSeeTableRecords($sortedAsc, inOrder: true);
});

it('supports bulk delete action', function () {
    asAdmin();

    $user = User::factory()->create([
        'name' => 'User to delete',
        'email' => 'delete@example.com',
    ]);

    Livewire::test(ListUsers::class)
        ->assertActionExists(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->selectTableRecords([$user->getKey()])
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertDatabaseMissing(User::class, [
        'id' => $user->id,
    ]);
});

it('renders the create user form and validates required fields', function () {
    asAdmin();

    Livewire::test(CreateUser::class)
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('email')
        ->assertFormFieldExists('avatar')
        ->fillForm([
            'name' => '',
            'email' => 'not-a-valid-email',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'email' => 'email']);
});

it('allows creating a user', function () {
    asAdmin();

    Storage::fake('public');

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(User::class, [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

it('allows creating a user with avatar', function () {
    asAdmin();

    Storage::fake('public');

    $avatar = UploadedFile::fake()->image('avatar.png', 100, 100);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'User With Avatar',
            'email' => 'avatar@example.com',
            'password' => 'password123',
            'avatar' => $avatar,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::query()->where('email', 'avatar@example.com')->first();

    expect($user)->not->toBeNull()->and($user->getFirstMediaUrl('avatar'))->not->toBeEmpty();
});

it('renders the edit user form', function () {
    asAdmin();

    $user = User::factory()->create([
        'name' => 'Original name',
        'email' => 'original@example.com',
        'title' => 'Original Title',
        'bio' => 'Original bio',
    ]);

    Livewire::test(EditUser::class, [
        'record' => $user->getKey(),
    ])
        ->assertOk()
        ->assertFormSet([
            'name' => 'Original name',
            'email' => 'original@example.com',
            'title' => 'Original Title',
            'bio' => 'Original bio',
        ]);
});

it('updates a user name', function () {
    asAdmin();

    $user = User::factory()->create([
        'name' => 'Old name',
        'email' => 'old@example.com',
    ]);

    Livewire::test(EditUser::class, [
        'record' => $user->getKey(),
    ])
        ->fillForm([
            'name' => 'New name',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($user->refresh()->name)->toBe('New name');
});

it('updates a user with new avatar', function () {
    asAdmin();

    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'User with avatar',
        'email' => 'avatar-update@example.com',
    ]);

    $newAvatar = UploadedFile::fake()->image('new-avatar.png', 200, 200);

    Livewire::test(EditUser::class, [
        'record' => $user->getKey(),
    ])
        ->fillForm([
            'name' => 'Updated name',
            'avatar' => $newAvatar,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh()->name)->toBe('Updated name')->and($user->getFirstMediaUrl('avatar'))->not->toBeEmpty();
});

it('renders the view user page with infolist', function () {
    asAdmin();

    $user = User::factory()->create([
        'name' => 'Viewable user',
        'email' => 'view@example.com',
        'title' => 'Developer',
        'bio' => 'A developer',
    ]);

    Livewire::test(ViewUser::class, [
        'record' => $user->getKey(),
    ])
        ->assertOk()
        ->assertSee('Viewable user')
        ->assertSee('view@example.com');
});
