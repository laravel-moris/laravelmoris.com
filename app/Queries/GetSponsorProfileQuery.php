<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Sponsor;
use Illuminate\Support\Facades\Storage;

readonly class GetSponsorProfileQuery
{
    /**
     * Load sponsor profile with their event history.
     */
    public function execute(Sponsor $sponsor): Sponsor
    {
        $sponsor->load([
            'events' => function ($query) {
                $query->orderBy('starts_at', 'desc');
            },
        ]);

        return $sponsor;
    }

    /**
     * Get the logo URL for a sponsor.
     */
    public function getLogoUrl(Sponsor $sponsor): string
    {
        $disk = Storage::disk('public');
        $placeholderLogoUrl = $disk->url('sponsors/placeholder.png');

        return filled($sponsor->logo) ? $disk->url($sponsor->logo) : $placeholderLogoUrl;
    }
}
