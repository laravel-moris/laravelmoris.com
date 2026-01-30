<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateUserFromOAuth;
use App\Data\Auth\OAuthUserData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

final class CallbackController
{
    public function __construct(
        private CreateUserFromOAuth $createUserFromOAuth,
    ) {}

    public function __invoke(string $provider): RedirectResponse
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();
            $data = OAuthUserData::fromSocialite($socialiteUser, $provider);
            $user = $this->createUserFromOAuth->execute($data);

            Auth::login($user);

            return redirect()->intended(route('home'));
        } catch (\Exception $e) {
            return to_route('login')
                ->withErrors(['oauth' => 'Authentication failed. Please try again.']);
        }
    }
}
