<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class LoginData extends Data
{
    public function __construct(
        #[Required, Email]
        public readonly string $email,

        #[Required]
        public readonly string $password,

        public readonly bool $remember = false,
    ) {}
}
