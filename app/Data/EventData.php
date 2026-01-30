<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\EventLocation;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class EventData extends Data
{
    public function __construct(
        public string $title,
        public CarbonImmutable $startsAt,
        public string $description,
        public CarbonImmutable $endsAt,
        public EventLocation $type,
        public EventLocationData $location,
    ) {}

    public static function make(
        string $title,
        string $date,
        string $startsAt,
        string $endsAt,
        string $timezone,
        string $description,
        EventLocation $type,
        EventLocationData $location,
    ): self {
        return new self(
            title: $title,
            startsAt: CarbonImmutable::createFromFormat('Y-m-d H:i', "$date $startsAt", $timezone),
            description: $description,
            endsAt: CarbonImmutable::createFromFormat('Y-m-d H:i', "$date $endsAt", $timezone),
            type: $type,
            location: $location,
        );
    }
}
