<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ProviderRedirectRequest;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

final class RedirectController
{
    public function __invoke(ProviderRedirectRequest $request, string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }
}
