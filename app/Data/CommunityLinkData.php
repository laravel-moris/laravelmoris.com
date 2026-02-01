<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class CommunityLinkData extends Data
{
    public function __construct(
        public string $iconKey,
        public string $name,
        public string $description,
        public string $url,
    ) {}

    public function isAvailable(): bool
    {
        return match ($this->url) {
            '#', '' => false,
            default => true,
        };
    }
}
