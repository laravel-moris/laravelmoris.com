<?php

declare(strict_types=1);

namespace App\Queries;

use App\Data\Auth\OAuthUserData;
use App\Models\User;

final class FindUserByOAuth
{
    public function execute(OAuthUserData $data): ?User
    {
        return User::query()
            ->where('provider', $data->provider)
            ->where('provider_id', $data->providerId)
            ->first();
    }
}
