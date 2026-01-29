<?php

declare(strict_types=1);

use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\Paper;
use App\Models\User;
use App\Queries\GetFeaturedSpeakersQuery;

test('it returns random featured speakers with approved papers', function () {
    $event = Event::factory()->create();
    $users = User::factory()->count(8)->create();

    foreach ($users as $user) {
        Paper::factory()->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => PaperStatus::Approved,
        ]);
    }

    $query = app(GetFeaturedSpeakersQuery::class);
    $speakers = $query->execute(6);

    expect($speakers)->toHaveCount(6)->and($speakers->first()->avatarUrl)->toBeString()->and($speakers->first()->name)->toBeString();
});
