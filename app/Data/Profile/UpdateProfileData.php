<?php

declare(strict_types=1);

namespace App\Data\Profile;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class UpdateProfileData extends Data
{
    public function __construct(
        #[Required, Max(255)]
        public readonly string $name,

        #[Nullable, Max(255)]
        public readonly ?string $title,

        #[Nullable, Max(1000)]
        public readonly ?string $bio,

        public readonly ?UploadedFile $avatar,
    ) {}
}
