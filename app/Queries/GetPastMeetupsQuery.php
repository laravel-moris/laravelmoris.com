<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Event;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;

readonly class GetPastMeetupsQuery
{
    /**
     * @param  array<int, int>  $excludeIds
     * @return Collection<int, Event>
     */
    public function execute(CarbonImmutable $nowUtc, int $limit, array $excludeIds = []): Collection
    {
        return Event::query()
            ->with(['location', 'speakers'])
            ->when(
                filled($excludeIds),
                fn ($q) => $q->whereKeyNot($excludeIds),
            )
            ->where('starts_at', '<', $nowUtc)
            ->latest('starts_at')
            ->limit($limit)
            ->get();
    }
}
