<?php

declare(strict_types=1);

use App\Actions\Auth\RegisterUser;
use App\Data\Auth\RegisterUserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses()->group('auth');

it('loads the login page successfully', function () {
    get(route('login'))
        ->assertSuccessful()
        ->assertViewIs('pages.auth.login');
});

it('redirects to github oauth provider', function () {
    $redirectUrl = 'https://github.com/login/oauth/authorize';

    Socialite::shouldReceive('driver')
        ->with('github')
        ->once()
        ->andReturnSelf();

    Socialite::shouldReceive('redirect')
        ->once()
        ->andReturn(redirect($redirectUrl));

    get(route('auth.provider', 'github'))
        ->assertRedirect($redirectUrl);
});

it('redirects to google oauth provider', function () {
    $redirectUrl = 'https://accounts.google.com/o/oauth2/auth';

    Socialite::shouldReceive('driver')
        ->with('google')
        ->once()
        ->andReturnSelf();

    Socialite::shouldReceive('redirect')
        ->once()
        ->andReturn(redirect($redirectUrl));

    get(route('auth.provider', 'google'))
        ->assertRedirect($redirectUrl);
});

it('rejects invalid oauth providers', function () {
    get(route('auth.provider', 'invalid'))
        ->assertSessionHasErrors(['provider']);
});

it('creates a new user from oauth callback', function () {
    $socialiteUser = mock(SocialiteUser::class, function ($mock) {
        $mock->shouldReceive('getId')->andReturn('123456');
        $mock->shouldReceive('getName')->andReturn('John Doe');
        $mock->shouldReceive('getEmail')->andReturn('john@example.com');
        $mock->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');
    });

    Socialite::shouldReceive('driver')
        ->with('github')
        ->once()
        ->andReturnSelf();

    Socialite::shouldReceive('user')
        ->once()
        ->andReturn($socialiteUser);

    expect(User::query()->where('provider', 'github')->where('provider_id', '123456')->exists())->toBeFalse();

    get(route('auth.callback', 'github'))
        ->assertRedirect(route('home'));

    $user = User::query()
        ->where('provider', 'github')
        ->where('provider_id', '123456')
        ->first();

    expect($user)->not->toBeNull()->and($user->name)->toBe('John Doe')->and($user->email)->toBe('john@example.com')->and($user->provider)->toBe('github')->and($user->provider_id)->toBe('123456');
});

it('logs in existing user from oauth callback', function () {
    $existingUser = User::factory()->create([
        'provider' => 'github',
        'provider_id' => '789012',
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $socialiteUser = mock(SocialiteUser::class, function ($mock) {
        $mock->shouldReceive('getId')->andReturn('789012');
        $mock->shouldReceive('getName')->andReturn('Jane Doe Updated');
        $mock->shouldReceive('getEmail')->andReturn('jane@example.com');
        $mock->shouldReceive('getAvatar')->andReturn('https://example.com/new-avatar.jpg');
    });

    Socialite::shouldReceive('driver')
        ->with('github')
        ->once()
        ->andReturnSelf();

    Socialite::shouldReceive('user')
        ->once()
        ->andReturn($socialiteUser);

    get(route('auth.callback', 'github'))
        ->assertRedirect(route('home'));

    $existingUser->refresh();

    expect($existingUser->name)->toBe('Jane Doe');
});

it('handles oauth callback with null email', function () {
    $socialiteUser = mock(SocialiteUser::class, function ($mock) {
        $mock->shouldReceive('getId')->andReturn('345678');
        $mock->shouldReceive('getName')->andReturn('No Email User');
        $mock->shouldReceive('getEmail')->andReturn(null);
        $mock->shouldReceive('getAvatar')->andReturn(null);
    });

    Socialite::shouldReceive('driver')
        ->with('github')
        ->once()
        ->andReturnSelf();

    Socialite::shouldReceive('user')
        ->once()
        ->andReturn($socialiteUser);

    get(route('auth.callback', 'github'))
        ->assertRedirect(route('home'));

    $user = User::query()
        ->where('provider', 'github')
        ->where('provider_id', '345678')
        ->first();

    expect($user)->not->toBeNull()->and($user->email)->toBe('345678@laravelmoris.com')->and($user->name)->toBe('No Email User');
});

it('handles oauth callback with null name', function () {
    $socialiteUser = mock(SocialiteUser::class, function ($mock) {
        $mock->shouldReceive('getId')->andReturn('901234');
        $mock->shouldReceive('getName')->andReturn(null);
        $mock->shouldReceive('getEmail')->andReturn('noname@example.com');
        $mock->shouldReceive('getAvatar')->andReturn(null);
    });

    Socialite::shouldReceive('driver')
        ->with('github')
        ->once()
        ->andReturnSelf();

    Socialite::shouldReceive('user')
        ->once()
        ->andReturn($socialiteUser);

    get(route('auth.callback', 'github'))
        ->assertRedirect(route('home'));

    $user = User::query()
        ->where('provider', 'github')
        ->where('provider_id', '901234')
        ->first();

    expect($user)->not->toBeNull()->and($user->name)->toBe('User');
});

it('redirects to login with error on oauth failure', function () {
    Socialite::shouldReceive('driver')
        ->with('github')
        ->once()
        ->andReturnSelf();

    Socialite::shouldReceive('user')
        ->once();

    get(route('auth.callback', 'github'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['oauth']);
});

it('logs out authenticated user', function () {
    $user = User::factory()->create();

    actingAs($user);

    expect(auth()->check())->toBeTrue();

    post(route('logout'))
        ->assertRedirect(route('home'));

    expect(auth()->check())->toBeFalse();
});

it('invalidates session on logout', function () {
    $user = User::factory()->create();

    actingAs($user);

    $oldSessionId = session()->getId();

    post(route('logout'));

    expect(session()->getId())->not->toBe($oldSessionId);
});

// Email/Password Registration Tests

it('loads the register page successfully', function () {
    get(route('register.create'))
        ->assertSuccessful()
        ->assertViewIs('pages.auth.register');
});

it('creates a new user with email and password', function () {
    $data = [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'title' => 'Developer',
        'bio' => 'A Laravel developer',
    ];

    expect(User::query()->where('email', 'newuser@example.com')->exists())->toBeFalse();

    post(route('register.store'), $data)
        ->assertRedirect(route('home'));

    expect(User::query()->where('email', 'newuser@example.com')->exists())->toBeTrue();

    $user = User::query()->where('email', 'newuser@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('New User')
        ->and($user->email)->toBe('newuser@example.com')
        ->and($user->title)->toBe('Developer')
        ->and($user->bio)->toBe('A Laravel developer');
});

it('validates required fields on registration', function () {
    post(route('register.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'password', 'password_confirmation']);
});

it('validates unique email on registration', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $data = [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ];

    post(route('register.store'), $data)
        ->assertSessionHasErrors(['email']);
});

it('validates password confirmation', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'DifferentPassword!',
    ];

    post(route('register.store'), $data)
        ->assertSessionHasErrors(['password']);
});

it('validates strong password requirements', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'weak',
        'password_confirmation' => 'weak',
    ];

    post(route('register.store'), $data)
        ->assertSessionHasErrors(['password']);
});

it('logs in user after registration', function () {
    $data = [
        'name' => 'Auto Login User',
        'email' => 'autologin@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ];

    post(route('register.store'), $data)
        ->assertRedirect(route('home'));

    expect(auth()->check())->toBeTrue()->and(auth()->user()->email)->toBe('autologin@example.com');
});

it('can upload avatar during registration', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpg', 400, 400);

    $data = new RegisterUserData(
        name: 'Avatar User',
        email: 'avatar@example.com',
        password: 'Password123!',
        password_confirmation: 'Password123!',
        title: 'Developer',
        bio: 'A Laravel developer',
        avatar: $file,
    );

    $user = app(RegisterUser::class)->execute($data);

    expect($user)->not->toBeNull()
        ->and($user->getFirstMedia('avatar'))->not->toBeNull();
});

it('handles pdf files during registration', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $data = new RegisterUserData(
        name: 'PDF User',
        email: 'pdf@example.com',
        password: 'Password123!',
        password_confirmation: 'Password123!',
        title: null,
        bio: null,
        avatar: $file,
    );

    // PDF files should be rejected by media library
    expect(fn () => app(RegisterUser::class)->execute($data))
        ->toThrow(FileUnacceptableForCollection::class);
});

it('handles large files during registration', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('large-avatar.jpg')->size(3000);

    $data = new RegisterUserData(
        name: 'Large File User',
        email: 'largefile@example.com',
        password: 'Password123!',
        password_confirmation: 'Password123!',
        title: null,
        bio: null,
        avatar: $file,
    );

    $user = app(RegisterUser::class)->execute($data);

    expect($user)->not->toBeNull()->and($user->getFirstMedia('avatar'))->not->toBeNull();
});

it('stores avatar in public storage', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('test-avatar.png', 200, 200);

    $data = new RegisterUserData(
        name: 'Storage User',
        email: 'storage@example.com',
        password: 'Password123!',
        password_confirmation: 'Password123!',
        title: null,
        bio: null,
        avatar: $file,
    );

    $user = app(RegisterUser::class)->execute($data);

    expect($user)->not->toBeNull()->and($user->getFirstMedia('avatar'))->not->toBeNull();
});

it('stores different avatar formats', function ($extension) {
    Storage::fake('public');

    $file = UploadedFile::fake()->image("avatar.{$extension}", 300, 300);

    $data = new RegisterUserData(
        name: "Format User {$extension}",
        email: "format{$extension}@example.com",
        password: 'Password123!',
        password_confirmation: 'Password123!',
        title: null,
        bio: null,
        avatar: $file,
    );

    $user = app(RegisterUser::class)->execute($data);

    expect($user)->not->toBeNull()
        ->and($user->getFirstMedia('avatar'))->not->toBeNull();
})->with(['jpg', 'png']);

// Email/Password Login Tests

it('logs in user with valid credentials', function () {
    $password = 'Password123!';
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'password' => bcrypt($password),
    ]);

    expect(auth()->check())->toBeFalse();

    post(route('login.store'), [
        'email' => 'login@example.com',
        'password' => $password,
    ])
        ->assertRedirect(route('home'));

    expect(auth()->check())->toBeTrue()->and(auth()->user()->id)->toBe($user->id);
});

it('fails login with invalid credentials', function () {
    User::factory()->create([
        'email' => 'fail@example.com',
        'password' => bcrypt('CorrectPassword123!'),
    ]);

    post(route('login.store'), [
        'email' => 'fail@example.com',
        'password' => 'WrongPassword456!',
    ])
        ->assertRedirect()
        ->assertSessionHasErrors(['email']);

    expect(auth()->check())->toBeFalse();
});

it('fails login with non-existent email', function () {
    post(route('login.store'), [
        'email' => 'nonexistent@example.com',
        'password' => 'SomePassword123!',
    ])
        ->assertRedirect()
        ->assertSessionHasErrors(['email']);

    expect(auth()->check())->toBeFalse();
});

it('validates required email on login', function () {
    post(route('login.store'), [
        'password' => 'Password123!',
    ])
        ->assertSessionHasErrors(['email']);
});

it('validates required password on login', function () {
    post(route('login.store'), [
        'email' => 'test@example.com',
    ])
        ->assertSessionHasErrors(['password']);
});

it('can remember user with remember option', function () {
    $password = 'Password123!';
    $user = User::factory()->create([
        'email' => 'remember@example.com',
        'password' => bcrypt($password),
    ]);

    post(route('login.store'), [
        'email' => 'remember@example.com',
        'password' => $password,
        'remember' => true,
    ])
        ->assertRedirect(route('home'));

    expect(auth()->check())->toBeTrue();
});
