<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Data\Auth\RegisterUserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class RegisterUser
{
    /**
     * @throws Throwable
     */
    public function execute(RegisterUserData $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => bcrypt($data->password),
                'title' => $data->title,
                'bio' => $data->bio,
            ]);

            if ($data->avatar instanceof UploadedFile) {
                $user->addMedia($data->avatar)
                    ->toMediaCollection('avatar');
            }

            return $user;
        });
    }
}
