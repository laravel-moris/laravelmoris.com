<?php

declare(strict_types=1);

namespace App\Queries;

use App\Data\HappeningNowData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use Illuminate\Support\Facades\Cache;

readonly class GetHappeningNowQuery
{
    // @note: Cache cannot cache null properly, using a placeholder
    private const NULL_RETURN = '__none__';

    public function execute(): ?HappeningNowData
    {
        $key = 'homepage:happening-now';
        $cached = Cache::get($key);

        if ($cached instanceof HappeningNowData) {
            return $cached;
        }

        if ($cached === self::NULL_RETURN) {
            return null;
        }

        $now = now('Indian/Mauritius')->utc()->toImmutable();

        $event = Event::query()
            ->with(['location', 'speakers'])
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->latest('starts_at')
            ->first();

        if (! $event) {
            Cache::put($key, self::NULL_RETURN, now()->addMinutes(30));

            return null;
        }

        $data = new HappeningNowData(
            id: $event->id,
            title: $event->title,
            startsAt: $event->starts_at->toImmutable(),
            endsAt: $event->ends_at->toImmutable(),
            speakersCount: $event->speakers->count(),
            ctaUrl: $this->locationUrl($event),
        );

        Cache::put($key, $data, now()->addHour());

        return $data;
    }

    private function locationUrl(Event $event): ?string
    {
        if (! $event->relationLoaded('location')) {
            $event->load('location');
        }

        if (! $event->type instanceof EventLocation) {
            return null;
        }

        return match ($event->type) {
            EventLocation::Online => $event->location instanceof OnlineLocation ? $event->location->url : null,
            EventLocation::Physical => $event->location instanceof PhysicalLocation ? $event->location->directions_url : null,
        };
    }
}
