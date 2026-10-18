<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUser;
use App\Data\Auth\RegisterUserData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final readonly class RegisterController
{
    public function __construct(
        private RegisterUser $registerUser,
    ) {}

    public function create(): View
    {
        return view('pages.auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = RegisterUserData::validateAndCreate($request->all());

        $user = $this->registerUser->execute($data);

        Auth::login($user);

        return to_route('verification.notice')
            ->with('success', 'Registration successful! Please verify your email.');
    }
}
