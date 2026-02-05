<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

test('it shows submission form for authenticated user', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $response = $this->actingAs($user)->get(route('papers.create', $event));

    $response->assertOk()
        ->assertViewIs('pages.papers.create')
        ->assertSee('Submit a Talk');
});

test('it redirects guest to login', function () {
    /** @var TestCase $this */
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $response = $this->post(route('papers.store', $event), [
        'title' => 'My Talk',
        'description' => 'Description',
    ]);

    $response->assertRedirect(route('login'));
});

test('it redirects with error for past events', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->subWeek(),
        'ends_at' => now()->subWeek()->addHours(4),
    ]);

    $response = $this->actingAs($user)->post(route('papers.store', $event), [
        'title' => 'My Talk',
        'description' => 'Description',
    ]);

    $response->assertRedirect(route('events.show', $event))
        ->assertSessionHas('error', 'You cannot submit a talk for past events.');
});

test('it prevents showing submission form for past events', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->subWeek(),
        'ends_at' => now()->subWeek()->addHours(4),
    ]);

    $response = $this->actingAs($user)->get(route('papers.create', $event));

    $response->assertRedirect(route('events.show', $event))
        ->assertSessionHas('error', 'You cannot submit a talk for past events.');
});

test('it submits talk successfully', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $response = $this->actingAs($user)->post(route('papers.store', $event), [
        'title' => 'My Talk Title',
        'description' => 'Talk description',
    ]);

    $response->assertRedirect(route('events.show', $event))
        ->assertSessionHas('success', 'Your talk has been submitted successfully!');
    expect($user->papers()->where('event_id', $event->id)->exists())->toBeTrue();
});

test('it updates user phone when submitting', function () {
    /** @var TestCase $this */
    $user = User::factory()->create(['phone' => null]);
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $this->actingAs($user)->post(route('papers.store', $event), [
        'title' => 'My Talk',
        'phone' => '57654321',
    ]);

    expect($user->refresh()->phone)->toBe('57654321');
});
