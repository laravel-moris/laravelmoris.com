<?php

declare(strict_types=1);

namespace App\Queries;

use App\Data\SpeakerCardData;
use App\Enums\PaperStatus;
use App\Models\User;
use Spatie\LaravelData\DataCollection;

readonly class GetFeaturedSpeakersQuery
{
    public function execute(int $limit = 6): DataCollection
    {
        return SpeakerCardData::collect(
            User::query()
                ->whereHas('papers', fn ($q) => $q->where('status', PaperStatus::Approved))
                ->inRandomOrder()
                ->limit($limit)
                ->get()
                ->map(fn (User $user) => new SpeakerCardData(
                    id: $user->id,
                    name: $user->name,
                    avatarUrl: (string) $user->avatar,
                    title: $user->title,
                    bio: $user->bio,
                )),
            DataCollection::class,
        );
    }
}
