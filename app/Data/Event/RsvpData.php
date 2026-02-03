<?php

declare(strict_types=1);

namespace App\Data\Event;

use App\Enums\RsvpStatus;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;

final class RsvpData extends Data
{
    public function __construct(
        #[Nullable, Enum(RsvpStatus::class)]
        public readonly ?string $status = null,
    ) {}
}
