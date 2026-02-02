<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Profile\StoreAvatar;
use App\Data\Auth\RegisterUserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class RegisterUser
{
    public function __construct(private StoreAvatar $storeAvatar) {}

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
                $user->avatar = $this->storeAvatarFile($data->avatar);
                $user->save();
            }

            return $user;
        });
    }

    private function storeAvatarFile(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();

        return $this->storeAvatar->execute($file->getContent(), $extension) ?? '';
    }
}
