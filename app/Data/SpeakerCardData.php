<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class SpeakerCardData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $avatarUrl,
        public ?string $title,
        public ?string $bio,
    ) {}
}
