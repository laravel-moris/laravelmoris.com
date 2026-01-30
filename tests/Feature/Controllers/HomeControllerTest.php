<?php

declare(strict_types=1);

use Tests\TestCase;

test('it displays the home page', function () {
    /** @var TestCase $this */
    $response = $this->get('/');

    $response->assertOk()
        ->assertViewIs('pages.home')
        ->assertSee('Laravel Moris');
});

test('it returns view with all data', function () {
    /** @var TestCase $this */
    $response = $this->get('/');

    $response->assertOk()
        ->assertViewHas('happeningNow')
        ->assertViewHas('meetups')
        ->assertViewHas('speakers')
        ->assertViewHas('communityLinks')
        ->assertViewHas('sponsors')
        ->assertViewHas('nextMeetupId');
});
