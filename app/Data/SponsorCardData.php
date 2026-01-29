<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class SponsorCardData extends Data
{
    public function __construct(
        public string $name,
        public string $logoUrl,
        public ?string $website,
    ) {}
}
