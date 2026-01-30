<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Data\Profile\UpdateProfileData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UpdateProfile
{
    public function execute(User $user, UpdateProfileData $data): User
    {
        $user->update([
            'name' => $data->name,
            'title' => $data->title,
            'bio' => $data->bio,
            'avatar' => $data->avatar instanceof UploadedFile ? $this->storeAvatarFile($data->avatar) : null,
        ]);

        return $user;
    }

    private function storeAvatarFile(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = sprintf("avatars/%s.{$extension}", Str::random());

        Storage::disk('public')->put($filename, $file->getContent());

        return $filename;
    }
}
