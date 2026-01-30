<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses()->group('auth');

it('loads the login page successfully', function () {
    get(route('login'))
        ->assertSuccessful()
        ->assertViewIs('auth.login');
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
