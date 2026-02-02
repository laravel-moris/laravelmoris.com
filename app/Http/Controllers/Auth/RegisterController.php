<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUser;
use App\Data\Auth\RegisterUserData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class RegisterController
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = RegisterUserData::validateAndCreate($request->all());

        $registerUser = app(RegisterUser::class);
        $user = $registerUser->execute($data);

        Auth::login($user);

        return to_route('home')->with('status', 'Account created successfully!');
    }
}
