<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Data\Auth\OAuthUserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class CreateUserFromOAuth
{
    public function __construct(
        private FindUserByOAuth $findUserByOAuth,
    ) {}

    public function execute(OAuthUserData $data): User
    {
        $user = $this->findUserByOAuth->execute($data);

        if ($user !== null) {
            $user->update([
                'oauth_token' => $data->token,
                'oauth_refresh_token' => $data->refreshToken,
            ]);

            return $user;
        }

        return User::query()->create([
            'provider' => $data->provider,
            'provider_id' => $data->providerId,
            'name' => $data->name,
            'email' => $data->email ?? "{$data->providerId}@github.com",
            'avatar' => $data->avatar,
            'password' => Hash::make((string) random_int(1, 100000)),
            'oauth_token' => $data->token,
            'oauth_refresh_token' => $data->refreshToken,
        ]);
    }
}
