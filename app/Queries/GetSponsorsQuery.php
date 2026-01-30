<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Sponsor;
use Illuminate\Support\Facades\Storage;

readonly class GetSponsorsQuery
{
    /**
     * Get all sponsors with their event counts.
     *
     * @return array<int, array<string, mixed>>
     */
    public function execute(): array
    {
        $disk = Storage::disk('public');
        $placeholderLogoUrl = $disk->url('sponsors/placeholder.png');

        return Sponsor::query()
            ->withCount('events')
            ->orderBy('name')
            ->get()
            ->map(fn (Sponsor $sponsor) => [
                'id' => $sponsor->id,
                'name' => $sponsor->name,
                'logoUrl' => filled($sponsor->logo) ? $disk->url($sponsor->logo) : $placeholderLogoUrl,
                'website' => $sponsor->website,
                'eventsCount' => $sponsor->events_count,
            ])
            ->all();
    }
}
