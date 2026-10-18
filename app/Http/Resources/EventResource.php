<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\EventLocation;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
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
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'rsvp_link' => $this->rsvpLink(),
        ];
    }

    /**
     * Get the RSVP link for the event.
     * Returns null if the event has already passed.
     */
    protected function rsvpLink(): ?string
    {
        if ($this->starts_at?->isPast()) {
            return null;
        }

        return route('events.rsvp', $this->resource);
    }

    /**
     * @return array<string, string>|null
     */
    protected function transformLocation(): ?array
    {
        if (! $this->resource->relationLoaded('location')) {
            return null;
        }

        if (! $this->resource->type instanceof EventLocation) {
            return null;
        }

        if ($this->resource->type === EventLocation::Physical) {
            if (! $this->resource->location instanceof PhysicalLocation) {
                return null;
            }

            return [
                'type' => EventLocation::Physical->value,
                'name' => $this->resource->location->venue_name,
                'address' => $this->resource->location->address,
                'city' => $this->resource->location->city,
                'directions_url' => $this->resource->location->directions_url,
            ];
        }

        if (! $this->resource->location instanceof OnlineLocation) {
            return null;
        }

        return [
            'type' => EventLocation::Online->value,
            'platform' => $this->resource->location->platform,
            'url' => $this->resource->location->url,
        ];
    }
}
