<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Sponsor;

readonly class GetSponsorsQuery
{
    /**
     * Get all sponsors with their event counts.
     *
     * @return array<int, array<string, mixed>>
     */
    public function execute(): array
    {

        return Sponsor::query()
            ->withCount('events')
            ->orderBy('name')
            ->get()
            ->map(fn (Sponsor $sponsor) => [
                'id' => $sponsor->id,
                'name' => $sponsor->name,
                'logoUrl' => (string) $sponsor->logo,
                'website' => $sponsor->website,
                'eventsCount' => $sponsor->events_count,
            ])
            ->all();
    }
}
