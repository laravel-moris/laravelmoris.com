<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

final class CreateOnlineEventData extends Data
{
    public function __construct(
        #[Required]
        public readonly string $title,
        public readonly ?string $description,
        #[Required]
        public readonly Carbon $startsAt,
        #[Required]
        public readonly Carbon $endsAt,
        public readonly ?string $platform,
        #[Required, Url]
        public readonly string $meetingUrl,
    ) {}
}
