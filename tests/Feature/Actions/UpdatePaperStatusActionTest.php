<?php

declare(strict_types=1);

use App\Actions\Paper\UpdatePaperStatusAction;
use App\Enums\PaperStatus;
use App\Models\Paper;
use App\Models\User;

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

describe('cache updates', function (): void {
    test('it increments papers_count_cache when changing to approved', function (): void {
        $user = User::factory()->create(['papers_count_cache' => 0]);
        $paper = Paper::factory()->create([
            'user_id' => $user->id,
            'status' => PaperStatus::Submitted,
        ]);

        app(UpdatePaperStatusAction::class)->execute($paper, PaperStatus::Approved);

        expect($user->fresh()->papers_count_cache)->toBe(1);
    });

    test('it decrements papers_count_cache when changing from approved', function (): void {
        $user = User::factory()->create(['papers_count_cache' => 0]);
        $paper = Paper::factory()->create([
            'user_id' => $user->id,
            'status' => PaperStatus::Approved,
        ]);
        // Cache is now 1 from the created observer
        expect($user->fresh()->papers_count_cache)->toBe(1);

        app(UpdatePaperStatusAction::class)->execute($paper, PaperStatus::Rejected);

        expect($user->fresh()->papers_count_cache)->toBe(0);
    });

    test('it does not change cache when status stays non-approved', function (): void {
        $user = User::factory()->create(['papers_count_cache' => 0]);
        $paper = Paper::factory()->create([
            'user_id' => $user->id,
            'status' => PaperStatus::Submitted,
        ]);

        app(UpdatePaperStatusAction::class)->execute($paper, PaperStatus::Draft);

        expect($user->fresh()->papers_count_cache)->toBe(0);
    });

    test('it does not change cache when status stays approved', function (): void {
        $user = User::factory()->create(['papers_count_cache' => 0]);
        $paper = Paper::factory()->create([
            'user_id' => $user->id,
            'status' => PaperStatus::Approved,
        ]);
        // Cache is now 1 from the created observer
        expect($user->fresh()->papers_count_cache)->toBe(1);

        app(UpdatePaperStatusAction::class)->execute($paper, PaperStatus::Approved);

        expect($user->fresh()->papers_count_cache)->toBe(1);
    });
});
