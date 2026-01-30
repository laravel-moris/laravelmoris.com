<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Data\Profile\UpdateProfileData;
use App\Models\User;
use Illuminate\Http\UploadedFile;

final readonly class UpdateProfile
{
    public function __construct(private StoreAvatar $storeAvatar) {}

    public function execute(User $user, UpdateProfileData $data): User
    {
        $user->update([
            'name' => $data->name,
            'title' => $data->title,
            'bio' => $data->bio,
            'avatar' => $data->avatar instanceof UploadedFile ? $this->storeAvatarFile($data->avatar) : $user->avatar,
        ]);

        return $user;
    }

    private function storeAvatarFile(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();

        return $this->storeAvatar->execute($file->getContent(), $extension);
    }
}
