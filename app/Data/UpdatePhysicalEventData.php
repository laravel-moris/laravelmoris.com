<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

final class UpdatePhysicalEventData extends Data
{
    public function __construct(
        public readonly int $eventId,
        public readonly string $title,
        public readonly ?string $description,
        public readonly Carbon $startsAt,
        public readonly Carbon $endsAt,
        public readonly int $locationId,
    ) {}
}
