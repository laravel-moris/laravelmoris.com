<?php

declare(strict_types=1);

use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\Sponsor;
use App\Queries\GetPastSponsorsQuery;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

test('it returns sponsors for past events with logo url fallback', function () {
    Storage::fake('public');
    Storage::disk('public')->put('sponsors/placeholder.png', 'x');
    Storage::disk('public')->put('sponsors/acme.png', 'x');

    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    $pastEvent = Event::factory()->create([
        'starts_at' => $now->subDays(2),
        'ends_at' => $now->subDays(2)->addHours(2),
        'type' => EventLocation::Online,
    ]);

    $withLogo = Sponsor::factory()->create([
        'name' => 'Acme',
        'logo' => 'sponsors/acme.png',
    ]);
    $withoutLogo = Sponsor::factory()->create([
        'name' => 'NoLogo',
        'logo' => null,
    ]);

    $pastEvent->sponsors()->attach([$withLogo->id, $withoutLogo->id]);

    $query = app(GetPastSponsorsQuery::class);
    $sponsors = $query->execute();

    expect($sponsors)->toHaveCount(2);

    $placeholderUrl = Storage::disk('public')->url('sponsors/placeholder.png');
    $noLogoCard = $sponsors->toCollection()->firstWhere('name', 'NoLogo');
    expect($noLogoCard->logoUrl)->toBe($placeholderUrl);

    Date::setTestNow();
});
