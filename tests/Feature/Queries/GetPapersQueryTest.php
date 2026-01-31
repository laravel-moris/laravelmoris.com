<?php

declare(strict_types=1);

use App\Models\Paper;
use App\Queries\GetPapersQuery;

test('it returns all papers', function () {
    Paper::factory()->count(3)->create();

    $result = app(GetPapersQuery::class)->execute();

    expect($result)->toHaveCount(3);
});

test('it returns papers sorted by latest first', function () {
    $olderPaper = Paper::factory()->create([
        'created_at' => now()->subDays(5),
    ]);
    $newerPaper = Paper::factory()->create([
        'created_at' => now()->subDay(),
    ]);

    $result = app(GetPapersQuery::class)->execute();

    expect($result)->toHaveCount(2)
        ->and($result->first()->id)->toBe($newerPaper->id)
        ->and($result->last()->id)->toBe($olderPaper->id);
});
