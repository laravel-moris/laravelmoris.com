<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Sponsor;

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
}
