<?php

declare(strict_types=1);

use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\Sponsor;
use App\Models\User;

beforeEach(function (): void {
    $this->event = Event::factory()->create([
        'title' => 'Laravel Workshop',
        'description' => 'A hands-on Laravel workshop',
        'type' => EventLocation::Physical,
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $this->pastEvent = Event::factory()->create([
        'title' => 'Past Laravel Meetup',
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->subMonth()->addHours(4),
    ]);
});

describe('events index', function (): void {
    it('displays the events listing page', function (): void {
        $response = $this->get(route('events.index'));

        $response->assertOk()
            ->assertViewIs('pages.events.index')
            ->assertViewHas('upcomingEvents')
            ->assertViewHas('pastEvents')
            ->assertSee('All Events');
    });

    it('shows upcoming events', function (): void {
        $response = $this->get(route('events.index'));

        $response->assertOk()
            ->assertSee('Upcoming Events')
            ->assertSee($this->event->title);
    });

    it('shows past events', function (): void {
        $response = $this->get(route('events.index'));

        $response->assertOk()
            ->assertSee('Past Events')
            ->assertSee($this->pastEvent->title);
    });

    it('shows event details on cards', function (): void {
        $speaker = User::factory()->create();
        $this->event->speakers()->attach($speaker, [
            'title' => 'Introduction to Laravel',
            'description' => 'A beginner-friendly talk',
            'status' => 'approved',
        ]);

        $attendee = User::factory()->create();
        $this->event->attendees()->attach($attendee, ['status' => 'confirmed']);

        $response = $this->get(route('events.index'));

        $response->assertOk()
            ->assertSee('1 speaker')
            ->assertSee('1 attendee');
    });

    it('displays empty state when no events exist', function (): void {
        Event::query()->delete();

        $response = $this->get(route('events.index'));

        $response->assertOk()
            ->assertSee('No Events Yet');
    });
});

describe('event show', function (): void {
    it('displays an event detail page', function (): void {
        $response = $this->get(route('events.show', $this->event));

        $response->assertOk()
            ->assertViewIs('pages.events.show')
            ->assertViewHas('event')
            ->assertSee($this->event->title)
            ->assertSee('Speakers')
            ->assertSee('Attendees');
    });

    it('shows event speakers', function (): void {
        $speaker = User::factory()->create([
            'name' => 'Jane Speaker',
            'title' => 'Senior Developer',
        ]);
        $this->event->speakers()->attach($speaker, [
            'title' => 'Advanced Eloquent',
            'description' => 'Deep dive into ORM',
            'status' => 'approved',
        ]);

        $response = $this->get(route('events.show', $this->event));

        $response->assertOk()
            ->assertSee('Jane Speaker')
            ->assertSee('Advanced Eloquent');
    });

    it('shows event attendees', function (): void {
        $attendee = User::factory()->create([
            'name' => 'John Attendee',
        ]);
        $this->event->attendees()->attach($attendee, ['status' => 'confirmed']);

        $response = $this->get(route('events.show', $this->event));

        $response->assertOk()
            ->assertSee('John Attendee');
    });

    it('shows event sponsors', function (): void {
        $sponsor = Sponsor::factory()->create([
            'name' => 'Tech Sponsor',
        ]);
        $this->event->sponsors()->attach($sponsor);

        $response = $this->get(route('events.show', $this->event));

        $response->assertOk()
            ->assertSee('Sponsors');
    });

    it('shows RSVP button for upcoming events', function (): void {
        $response = $this->get(route('events.show', $this->event));

        $response->assertOk()
            ->assertSee('Log in to RSVP');
    });

    it('shows RSVP buttons for authenticated users', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('events.show', $this->event));

        $response->assertOk()
            ->assertSee('Going')
            ->assertSee('Maybe')
            ->assertSee('Not Going');
    });

    it('shows past chip for past events', function (): void {
        $response = $this->get(route('events.show', $this->pastEvent));

        $response->assertOk()
            ->assertSee('Past');
    });

    it('includes back to events link', function (): void {
        $response = $this->get(route('events.show', $this->event));

        $response->assertOk()
            ->assertSee('All Events');
    });
});
