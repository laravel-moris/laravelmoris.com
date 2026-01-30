<?php

declare(strict_types=1);

namespace App\Data\Paper;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class SubmitPaperData extends Data
{
    public function __construct(
        #[Required, Max(255)]
        public readonly string $title,

        #[Nullable, Max(2000)]
        public readonly ?string $description,

        #[Nullable, Max(20), Regex('/^5\d{7}$/')]
        public readonly ?string $phone,

        #[Nullable, Email, Max(255)]
        public readonly ?string $secondaryEmail,
    ) {}
}
