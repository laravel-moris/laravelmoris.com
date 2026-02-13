<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

final class CreatePhysicalEventData extends Data
{
    public function __construct(
        public string $title,
        public ?string $description,
        public Carbon $starts_at,
        public Carbon $ends_at,
        public int $location_id,
    ) {}
}
