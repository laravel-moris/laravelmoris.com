<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\Authenticate;
use App\Data\Auth\LoginData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class LoginController
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = LoginData::validateAndCreate($request->all());

        app(Authenticate::class)->execute($data);

        return to_route('home')->with('status', 'Welcome back!');
    }
}
