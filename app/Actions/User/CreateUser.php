<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Data\User\CreateUserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

final readonly class CreateUser
{
    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function execute(CreateUserData $data): User
    {
        $user = User::query()->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password ? bcrypt($data->password) : null,
            'title' => $data->title,
            'bio' => $data->bio,
        ]);

        if ($data->avatar instanceof UploadedFile) {
            $user->addMedia($data->avatar)
                ->toMediaCollection('avatar');
        }

        return $user;
    }
}
