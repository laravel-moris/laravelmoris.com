<?php

declare(strict_types=1);

use App\Models\Sponsor;
use App\Queries\GetSponsorsQuery;

test('it returns all sponsors ordered by name', function () {
    Sponsor::factory()->create(['name' => 'Zeta Corp']);
    Sponsor::factory()->create(['name' => 'Alpha Inc']);

    $result = app(GetSponsorsQuery::class)->execute();

    expect($result[0]['name'])->toBe('Alpha Inc')
        ->and($result[1]['name'])->toBe('Zeta Corp');
});

test('it includes events count', function () {
    $sponsor = Sponsor::factory()->create();

    $result = app(GetSponsorsQuery::class)->execute();

    $returnedSponsor = collect($result)->firstWhere('id', $sponsor->id);
    expect($returnedSponsor['eventsCount'])->toBe(0);
});

test('it returns empty when no sponsors exist', function () {
    Sponsor::query()->delete();

    $result = app(GetSponsorsQuery::class)->execute();

    expect($result)->toBeEmpty();
});
