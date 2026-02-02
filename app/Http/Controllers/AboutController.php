<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

final readonly class AboutController
{
    /**
     * Display the About Us page.
     */
    public function index(): View
    {
        $teamMembers = User::query()
            ->whereNotNull('title')
            ->orWhere('bio', 'LIKE', '%founder%')
            ->orWhere('bio', 'LIKE', '%organizer%')
            ->limit(5)
            ->get();

        return view('pages.about', [
            'teamMembers' => $teamMembers,
        ]);
    }
}
