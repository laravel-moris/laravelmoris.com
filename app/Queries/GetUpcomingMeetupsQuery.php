<?php

declare(strict_types=1);

namespace App\Queries;

use App\Data\MeetupCardData;
use App\Data\SpeakerAvatarData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\DataCollection;

readonly class GetUpcomingMeetupsQuery
{
    public function __construct(
        private GetHappeningNowQuery $getHappeningNowQuery,
        private GetPastMeetupsQuery $getPastMeetupsQuery,
    ) {}

    public function execute(int $limit = 6): DataCollection
    {
        $nowUtc = $this->nowUtc();
        $excludeEventIds = $this->excludeEventIds();

        $upcomingEvents = $this->getUpcomingEvents(nowUtc: $nowUtc, limit: $limit, excludeEventIds: $excludeEventIds);
        $pastEvents = $this->getPastFillEvents(
            nowUtc: $nowUtc,
            limit: $limit,
            upcomingCount: $upcomingEvents->count(),
            excludeEventIds: $excludeEventIds,
        );

        $events = $upcomingEvents->concat($pastEvents);
        $featuredEventId = $this->featuredEventId($events);

        return MeetupCardData::collect(
            $events->map(fn (Event $event) => $this->toMeetupCard($event, $featuredEventId)),
            DataCollection::class,
        );
    }

    private function nowUtc(): CarbonImmutable
    {
        return now('Indian/Mauritius')->utc()->toImmutable();
    }

    /**
     * @return array<int, int>
     */
    private function excludeEventIds(): array
    {
        $happeningNow = $this->getHappeningNowQuery->execute();

        if (! $happeningNow) {
            return [];
        }

        return [$happeningNow->id];
    }

    /**
     * @param  array<int, int>  $excludeEventIds
     * @return Collection<int, Event>
     */
    private function getUpcomingEvents(CarbonImmutable $nowUtc, int $limit, array $excludeEventIds): Collection
    {
        return Event::query()
            ->with(['location', 'speakers'])
            ->when(
                filled($excludeEventIds),
                fn ($query) => $query->whereKeyNot($excludeEventIds),
            )
            ->where('starts_at', '>=', $nowUtc)
            ->oldest('starts_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @param  array<int, int>  $excludeEventIds
     * @return Collection<int, Event>
     */
    private function getPastFillEvents(CarbonImmutable $nowUtc, int $limit, int $upcomingCount, array $excludeEventIds): Collection
    {
        $remaining = $limit - $upcomingCount;

        if ($remaining <= 0) {
            return new Collection;
        }

        return $this->getPastMeetupsQuery->execute($nowUtc, $remaining, $excludeEventIds);
    }

    /**
     * @param  Collection<int, Event>  $events
     */
    private function featuredEventId(Collection $events): ?int
    {
        return $events
            ->first(fn (Event $event) => $event->starts_at?->isFuture() === true)
            ?->id;
    }

    private function toMeetupCard(Event $event, ?int $featuredEventId): MeetupCardData
    {
        return new MeetupCardData(
            id: $event->id,
            title: $event->title,
            startsAt: $event->starts_at->toImmutable(),
            endsAt: $event->ends_at?->toImmutable(),
            featured: $featuredEventId === $event->id,
            speakers: $this->speakerAvatars($event),
            ctaUrl: $this->locationUrl($event),
        );
    }

    /**
     * @return DataCollection<int, SpeakerAvatarData>
     */
    private function speakerAvatars(Event $event): DataCollection
    {
        return SpeakerAvatarData::collect(
            $event->speakers
                ->take(3)
                ->map(fn (User $speaker) => new SpeakerAvatarData(
                    name: (string) $speaker->name,
                    avatarUrl: (string) $speaker->avatar,
                )),
            DataCollection::class,
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
