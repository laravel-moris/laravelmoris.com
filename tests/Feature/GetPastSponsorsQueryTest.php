<?php

declare(strict_types=1);

use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\Sponsor;
use App\Queries\GetPastSponsorsQuery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;

test('it returns sponsors for past events with logo url fallback', function () {
    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    $pastEvent = Event::factory()->create([
        'starts_at' => $now->subDays(2),
        'ends_at' => $now->subDays(2)->addHours(2),
        'type' => EventLocation::Online,
    ]);

    $withLogo = Sponsor::factory()->create(['name' => 'Acme']);
    $withoutLogo = Sponsor::factory()->create(['name' => 'NoLogo']);

    // Add logo to media library for $withLogo
    $file = UploadedFile::fake()->image('logo.png', 100, 100);
    $withLogo->addMedia($file)->toMediaCollection('logo');

    $pastEvent->sponsors()->attach([$withLogo->id, $withoutLogo->id]);

    $query = app(GetPastSponsorsQuery::class);
    $sponsors = $query->execute();

    expect($sponsors)->toHaveCount(2);

    $noLogoCard = $sponsors->toCollection()->firstWhere('name', 'NoLogo');
    expect($noLogoCard->logoUrl)->toBe('https://avatars.laravel.cloud/NoLogo');

    $withLogoCard = $sponsors->toCollection()->firstWhere('name', 'Acme');
    expect($withLogoCard->logoUrl)->not->toBe('https://avatars.laravel.cloud/Acme');

    Date::setTestNow();
});
