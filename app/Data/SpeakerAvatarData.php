<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class SpeakerAvatarData extends Data
{
    public function __construct(
        public string $name,
        public string $avatarUrl,
    ) {}
}
