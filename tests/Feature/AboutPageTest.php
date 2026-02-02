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
