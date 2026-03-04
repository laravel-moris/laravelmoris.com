<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\get;

it('can access the about page', function () {
    $response = get(route('about'));

    $response->assertOk()
        ->assertViewIs('pages.about')
        ->assertSee('About')
        ->assertSee('Laravel Moris')
        ->assertSee('Meet the Team');
});

it('shows team members on the about page', function () {
    User::factory()->create([
        'name' => 'Nice Human Being',
        'title' => 'Vibe Engineer',
    ]);

    $response = get(route('about'));

    $response->assertOk()
        ->assertSee('Nice Human Being')
        ->assertSee('Vibe Engineer');
});

it('eager loads media for team members on the about page', function () {
    User::factory()->create([
        'name' => 'Team Member',
        'title' => 'Organizer',
    ]);

    $response = get(route('about'));

    $response->assertOk()
        ->assertViewHas('teamMembers', function ($teamMembers): bool {
            return $teamMembers->every(fn (User $member): bool => $member->relationLoaded('media'));
        });
});
