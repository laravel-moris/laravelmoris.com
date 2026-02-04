<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Data\User\UpdateUserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

final readonly class UpdateUser
{
    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function execute(User $user, UpdateUserData $data): User
    {
        $updateData = [
            'name' => $data->name,
            'email' => $data->email,
            'title' => $data->title,
            'bio' => $data->bio,
        ];

        if ($data->password !== null) {
            $updateData['password'] = bcrypt($data->password);
        }

        $user->update($updateData);

        // Handle avatar deletion
        if ($data->deleteAvatar) {
            $user->clearMediaCollection('avatar');
        }

        // Handle new avatar upload
        if ($data->avatar instanceof UploadedFile) {
            $user->clearMediaCollection('avatar');
            $user->addMedia($data->avatar)
                ->toMediaCollection('avatar');
        }

        return $user->fresh();
    }
}
