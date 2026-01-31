<?php

declare(strict_types=1);

use App\Actions\Paper\UpdatePaperStatusAction;
use App\Enums\PaperStatus;
use App\Models\Paper;

test('it updates paper status to approved', function () {
    $paper = Paper::factory()->create([
        'status' => PaperStatus::Draft,
    ]);

    app(UpdatePaperStatusAction::class)->execute($paper, PaperStatus::Approved);

    expect($paper->refresh()->status)->toBe(PaperStatus::Approved);
});

test('it updates paper status to rejected', function () {
    $paper = Paper::factory()->create([
        'status' => PaperStatus::Submitted,
    ]);

    app(UpdatePaperStatusAction::class)->execute($paper, PaperStatus::Rejected);

    expect($paper->refresh()->status)->toBe(PaperStatus::Rejected);
});

test('it updates paper status to draft', function () {
    $paper = Paper::factory()->create([
        'status' => PaperStatus::Submitted,
    ]);

    app(UpdatePaperStatusAction::class)->execute($paper, PaperStatus::Draft);

    expect($paper->refresh()->status)->toBe(PaperStatus::Draft);
});

test('it updates paper status to submitted', function () {
    $paper = Paper::factory()->create([
        'status' => PaperStatus::Draft,
    ]);

    app(UpdatePaperStatusAction::class)->execute($paper, PaperStatus::Submitted);

    expect($paper->refresh()->status)->toBe(PaperStatus::Submitted);
});
