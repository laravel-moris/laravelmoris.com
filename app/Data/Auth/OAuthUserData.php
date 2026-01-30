<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Laravel\Socialite\Contracts\User as SocialiteUser;
use Spatie\LaravelData\Data;

final class OAuthUserData extends Data
{
    public function __construct(
        public readonly string $provider,
        public readonly string $providerId,
        public readonly string $name,
        public readonly ?string $email,
        public readonly ?string $avatar,
    ) {}

    public static function fromSocialite(SocialiteUser $socialiteUser, string $provider): self
    {
        return new self(
            provider: $provider,
            providerId: (string) $socialiteUser->getId(),
            name: $socialiteUser->getName() ?? 'User',
            email: $socialiteUser->getEmail(),
            avatar: $socialiteUser->getAvatar(),
        );
    }
}
