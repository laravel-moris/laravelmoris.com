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
        return view('pages.profile.index', [
            'user' => Auth::user(),
        ]);
    }

    public function edit(): View
    {
        return view('pages.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(UpdateProfileData $data): RedirectResponse
    {
        $user = Auth::user();

        $data = new UpdateProfileData(
            name: $data->name,
            title: $data->title,
            bio: $data->bio,
            avatar: request()->file('avatar'),
        );

        $this->updateProfile->execute($user, $data);

        return to_route('profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}
