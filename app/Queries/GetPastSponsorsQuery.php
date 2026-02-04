<?php

declare(strict_types=1);

namespace App\Queries;

use App\Data\SponsorCardData;
use App\Models\Sponsor;
use Spatie\LaravelData\DataCollection;

readonly class GetPastSponsorsQuery
{
    public function execute(): DataCollection
    {
        $now = now('Indian/Mauritius')->utc()->toImmutable();

        return SponsorCardData::collect(
            Sponsor::query()
                ->whereHas('events', fn ($q) => $q->where('starts_at', '<', $now))
                ->orderBy('name')
                ->get()
                ->map(function (Sponsor $sponsor) {
                    $logoUrl = $sponsor->getFirstMediaUrl('logo', 'webp');

                    return new SponsorCardData(
                        id: $sponsor->id,
                        name: $sponsor->name,
                        logoUrl: $sponsor->logo,
                        website: $sponsor->website,
                    );
                }),
            DataCollection::class,
        );
    }
}
