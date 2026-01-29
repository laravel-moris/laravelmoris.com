<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class HappeningNowData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public CarbonImmutable $startsAt,
        public CarbonImmutable $endsAt,
        public int $speakersCount,
        public ?string $ctaUrl,
    ) {}
}
