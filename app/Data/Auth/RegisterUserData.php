<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

final class RegisterUserData extends Data
{
    public function __construct(
        #[Required, Max(255)]
        public readonly string $name,

        #[Required, Email, Max(255), Unique('users')]
        public readonly string $email,

        #[Required, Password(min: 8, letters: true, mixedCase: true, numbers: true, symbols: true), Confirmed]
        public readonly string $password,

        #[Required]
        public readonly string $password_confirmation,

        #[Nullable, Max(255)]
        public readonly ?string $title,

        #[Nullable, Max(1000)]
        public readonly ?string $bio,

        #[Nullable, Image, Max(2048)]
        public readonly ?UploadedFile $avatar,
    ) {}
}
