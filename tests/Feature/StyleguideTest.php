<?php

declare(strict_types=1);

use Tests\TestCase;

test('it returns 200 for the styleguide page', function () {
    /** @var TestCase $this */
    $this->get('/styleguide')
        ->assertOk()
        ->assertSeeText('Style Guide');
});
