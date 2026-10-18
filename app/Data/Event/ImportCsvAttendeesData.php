<?php

declare(strict_types=1);

namespace App\Data\Event;

use Spatie\LaravelData\Data;

final class ImportCsvAttendeesData extends Data
{
    public function __construct(
        public string $csvData,
    ) {}
}
