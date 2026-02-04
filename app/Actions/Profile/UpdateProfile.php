<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Data\Profile\UpdateProfileData;
use App\Models\User;
use Illuminate\Http\UploadedFile;

final readonly class UpdateProfile
{
    public function execute(User $user, UpdateProfileData $data): User
    {
        $updateData = [
            'name' => $data->name,
            'title' => $data->title,
            'bio' => $data->bio,
        ];

        if ($data->avatar instanceof UploadedFile) {
            // Delete existing avatar from media library
            $user->clearMediaCollection('avatar');

            // Add new avatar to media library
            $user->addMedia($data->avatar)
                ->toMediaCollection('avatar');
        }

        $user->update($updateData);

        return $user;
    }
}
