<?php

declare(strict_types=1);

namespace App\Queries;

use App\Data\SponsorCardData;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\DataCollection;

readonly class GetPastSponsorsQuery
{
    public function execute(): DataCollection
    {
        $now = now('Indian/Mauritius')->utc()->toImmutable();
        $disk = Storage::disk('public');
        $placeholderLogoUrl = $disk->url('sponsors/placeholder.png');

        return SponsorCardData::collect(
            Sponsor::query()
                ->whereHas('events', fn ($q) => $q->where('starts_at', '<', $now))
                ->orderBy('name')
                ->get()
                ->map(fn (Sponsor $sponsor) => new SponsorCardData(
                    name: $sponsor->name,
                    logoUrl: filled($sponsor->logo) ? $disk->url($sponsor->logo) : $placeholderLogoUrl,
                    website: $sponsor->website,
                )),
            DataCollection::class,
        );
    }
}
