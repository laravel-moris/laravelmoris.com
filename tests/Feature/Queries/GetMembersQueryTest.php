<?php

declare(strict_types=1);

use App\Models\User;
use App\Queries\GetMembersQuery;

test('it returns paginated members', function () {
    User::factory()->count(15)->create();

    $result = app(GetMembersQuery::class)->execute();

    expect($result->total())->toBe(15)
        ->and($result->perPage())->toBe(12)
        ->and($result->currentPage())->toBe(1);
});

test('it includes papers and rsvps counts', function () {
    $user = User::factory()->create();

    $result = app(GetMembersQuery::class)->execute();

    $returnedUser = $result->where('id', $user->id)->first();
    expect($returnedUser->papers_count)->toBe(0)
        ->and($returnedUser->rsvps_count)->toBe(0);
});

test('it orders by name', function () {
    User::factory()->create(['name' => 'Zoe User']);
    User::factory()->create(['name' => 'Alice User']);

    $result = app(GetMembersQuery::class)->execute();

    expect($result->first()->name)->toBe('Alice User');
});

test('it returns empty when no members exist', function () {
    User::query()->delete();

    $result = app(GetMembersQuery::class)->execute();

    expect($result->total())->toBe(0);
});
