<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\EventLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
        ];
    }

    /**
     * Transform location into a unified format.
     */
    protected function transformLocation(): ?array
    {
        if (! $this->relationLoaded('location')) {
            return null;
        }

        if (! $this->type instanceof EventLocation) {
            return null;
        }

        $location = $this->location;

        return match ($this->type) {
            EventLocation::Physical => [
                'type' => 'physical',
                'name' => $location?->venue_name,
                'address' => $location?->address,
                'city' => $location?->city,
                'directions_url' => $location?->directions_url,
            ],
            EventLocation::Online => [
                'type' => 'online',
                'platform' => $location?->platform,
                'url' => $location?->url,
            ],
        };
    }
}
