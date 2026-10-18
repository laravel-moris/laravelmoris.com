<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Profile\UpdateProfile;
use App\Data\Profile\UpdateProfileData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final readonly class ProfileController
{
    public function __construct(
        private UpdateProfile $updateProfile,
    ) {}

    public function index(): View
    {
        $user = Auth::user();

        $rsvps = $user->rsvps()
            ->withPivot('status')
            ->orderBy('event_user.created_at', 'desc')
            ->paginate(10, ['*'], 'rsvps_page');

        $papers = $user->papers()
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'papers_page');

        return view('pages.profile.index', compact('user', 'rsvps', 'papers'));
    }

    public function edit(): View
    {
        return view('pages.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(UpdateProfileData $data): RedirectResponse
    {
        $this->updateProfile->execute(Auth::user(), $data);

        return to_route('profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}
