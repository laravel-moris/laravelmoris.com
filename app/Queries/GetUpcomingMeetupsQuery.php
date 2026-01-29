<?php

declare(strict_types=1);

namespace App\Queries;

use App\Data\MeetupCardData;
use App\Data\SpeakerAvatarData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use Spatie\LaravelData\DataCollection;

readonly class GetUpcomingMeetupsQuery
{
    public function __construct(
        private GetHappeningNowQuery $getHappeningNowQuery,
        private GetPastMeetupsQuery $getPastMeetupsQuery,
    ) {}

    public function execute(int $limit = 6): DataCollection
    {
        $now = now('Indian/Mauritius')->utc()->toImmutable();
        $happeningNow = $this->getHappeningNowQuery->execute();
        $excludeIds = $happeningNow ? [$happeningNow->id] : [];

        $upcoming = Event::query()
            ->with(['location', 'speakers'])
            ->when(
                filled($excludeIds),
                fn ($q) => $q->whereKeyNot($excludeIds),
            )
            ->where('starts_at', '>=', $now)
            ->oldest('starts_at')
            ->limit($limit)
            ->get();

        $remaining = $limit - $upcoming->count();

        $past = collect();

        if ($remaining > 0) {
            $past = $this->getPastMeetupsQuery->execute($now, $remaining, $excludeIds);
        }

        $events = $upcoming->concat($past);
        $featuredEventId = $events
            ->first(fn (Event $event) => $event->starts_at?->isFuture() === true)
            ?->id;

        return MeetupCardData::collect(
            $events->map(fn (Event $event) => $this->toMeetupCard($event, $featuredEventId)),
            DataCollection::class,
        );
    }

    private function toMeetupCard(Event $event, ?int $featuredEventId): MeetupCardData
    {
        return new MeetupCardData(
            id: $event->id,
            title: $event->title,
            startsAt: $event->starts_at->toImmutable(),
            endsAt: $event->ends_at?->toImmutable(),
            featured: $featuredEventId === $event->id,
            speakers: SpeakerAvatarData::collect(
                $event->speakers
                    ->take(3)
                    ->map(fn ($speaker) => new SpeakerAvatarData(
                        name: (string) $speaker->name,
                        avatarUrl: (string) $speaker->avatar,
                    )),
                DataCollection::class,
            ),
            ctaUrl: $this->locationUrl($event),
        );
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
