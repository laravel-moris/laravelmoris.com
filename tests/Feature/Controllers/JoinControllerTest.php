<?php

declare(strict_types=1);

use Tests\TestCase;

test('it displays the join community page', function () {
    /** @var TestCase $this */
    $response = $this->get(route('join.index'));

    $response->assertOk()
        ->assertViewIs('pages.join.index')
        ->assertViewHas('communityLinks')
        ->assertSee('Join Our Community');
});

test('it shows community links from query', function () {
    /** @var TestCase $this */
    $response = $this->get(route('join.index'));

    $response->assertOk()
        ->assertSee('Discord')
        ->assertSee('WhatsApp');
});
