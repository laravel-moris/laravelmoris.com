<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Data\Auth\LoginData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final readonly class Authenticate
{
    public function execute(LoginData $data): bool
    {
        if (! Auth::attempt(['email' => $data->email, 'password' => $data->password], $data->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        session()->regenerate();

        return true;
    }
}
