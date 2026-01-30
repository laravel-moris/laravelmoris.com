<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Profile\DownloadAvatar;
use App\Data\Auth\OAuthUserData;
use App\Models\User;
use App\Queries\FindUserByOAuth;
use Illuminate\Support\Str;

final readonly class CreateUserFromOAuth
{
    public function __construct(
        private FindUserByOAuth $findUserByOAuth,
        private DownloadAvatar $downloadAvatar
    ) {}

    public function execute(OAuthUserData $data): User
    {
        $user = $this->findUserByOAuth->execute($data);

        if (filled($user)) {
            return $user;
        }

        return User::query()->create([
            'provider' => $data->provider,
            'provider_id' => $data->providerId,
            'name' => $data->name,
            'email' => $data->email ?? "{$data->providerId}@laravelmoris.com",
            'avatar' => $this->downloadAvatar->execute($data->avatar),
            'password' => Str::random(),
        ]);
    }
}
