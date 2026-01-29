<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class MeetupCardData extends Data
{
    /**
     * @param  DataCollection<int, SpeakerAvatarData>  $speakers
     */
    public function __construct(
        public int $id,
        public string $title,
        public CarbonImmutable $startsAt,
        public ?CarbonImmutable $endsAt,
        public bool $featured,
        public DataCollection $speakers,
        public ?string $ctaUrl,
    ) {}
}
