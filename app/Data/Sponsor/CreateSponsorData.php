<?php

declare(strict_types=1);

namespace App\Data\Sponsor;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateSponsorData extends Data
{
    public function __construct(
        public string $name,
        public string $website,
        public ?UploadedFile $logo = null,
    ) {}
}
