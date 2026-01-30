<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use Spatie\LaravelData\Data;

class EventLocationData extends Data
{
    /**
     * @param  class-string<PhysicalLocation|OnlineLocation>  $class
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public string $class,
        public array $attributes,
    ) {}
}
