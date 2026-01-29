<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController
{
    public function __invoke(Request $request): View
    {
        return view('index');
    }
}
