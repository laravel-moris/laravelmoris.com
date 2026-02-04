<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\Sponsor;
use App\Queries\GetSponsorProfileQuery;
use Illuminate\Http\UploadedFile;

test('it loads events ordered by date descending', function () {
    $sponsor = Sponsor::factory()->create();
    $event1 = Event::factory()->create([
        'starts_at' => now()->subMonths(2),
        'ends_at' => now()->subMonths(2)->addHours(4),
    ]);
    $event2 = Event::factory()->create([
        'starts_at' => now()->subWeek(),
        'ends_at' => now()->subWeek()->addHours(4),
    ]);

    $sponsor->events()->attach([$event1->id, $event2->id]);

    $result = app(GetSponsorProfileQuery::class)->execute($sponsor);

    expect($result->relationLoaded('events'))->toBeTrue()
        ->and($result->events->first()->id)->toBe($event2->id)
        ->and($result->events->last()->id)->toBe($event1->id);
});

test('it returns placeholder logo when no logo set', function () {
    $sponsor = Sponsor::factory()->create();

    expect($sponsor->logo)->toContain('avatars.laravel.cloud');
});

test('it returns logo URL when logo is set', function () {
    $sponsor = Sponsor::factory()->create();

    // Add a logo to the media library
    $file = UploadedFile::fake()->image('logo.png', 100, 100);
    $sponsor->addMedia($file)->toMediaCollection('logo');

    // The URL should contain the conversion path
    expect($sponsor->logo)->toContain('/conversions/');
});

test('it returns the same sponsor instance with relations loaded', function () {
    $sponsor = Sponsor::factory()->create();

    $result = app(GetSponsorProfileQuery::class)->execute($sponsor);

    expect($result->is($sponsor))->toBeTrue();
});
