<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

final class UpdateOnlineEventData extends Data
{
    public function __construct(
        public int $eventId,
        #[Required]
        public string $title,
        public ?string $description,
        #[Required]
        public Carbon $starts_at,
        #[Required]
        public Carbon $ends_at,
        public ?string $platform,
        #[Required, Url]
        public string $meeting_url,
    ) {}
}
