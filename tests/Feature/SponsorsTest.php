<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\Sponsor;

beforeEach(function (): void {
    $this->sponsor = Sponsor::factory()->create([
        'name' => 'Tech Corp',
        'website' => 'https://techcorp.com',
    ]);
});

describe('sponsors index', function (): void {
    it('displays the sponsors listing page', function (): void {
        $response = $this->get(route('sponsors.index'));

        $response->assertOk()
            ->assertViewIs('pages.sponsors.index')
            ->assertViewHas('sponsors')
            ->assertSee('Our Sponsors')
            ->assertSee($this->sponsor->name);
    });

    it('displays sponsor event counts', function (): void {
        $event = Event::factory()->create();
        $this->sponsor->events()->attach($event);

        $response = $this->get(route('sponsors.index'));

        $response->assertOk()
            ->assertSee('1')
            ->assertSee('event')
            ->assertSee('sponsored');
    });

    it('displays sponsor website domain', function (): void {
        $response = $this->get(route('sponsors.index'));

        $response->assertOk()
            ->assertSee('techcorp.com');
    });

    it('displays empty state when no sponsors exist', function (): void {
        Sponsor::query()->delete();

        $response = $this->get(route('sponsors.index'));

        $response->assertOk()
            ->assertSee('No Sponsors Yet')
            ->assertSee('Become a Sponsor');
    });
});

describe('sponsor show', function (): void {
    it('displays a sponsor profile', function (): void {
        $response = $this->get(route('sponsors.show', $this->sponsor));

        $response->assertOk()
            ->assertViewIs('pages.sponsors.show')
            ->assertViewHas('sponsor')
            ->assertViewHas('logoUrl')
            ->assertSee($this->sponsor->name)
            ->assertSee('Events Sponsored');
    });

    it('displays sponsor event history', function (): void {
        $event = Event::factory()->create([
            'title' => 'Laravel Meetup #1',
            'starts_at' => now()->subMonth(),
        ]);
        $this->sponsor->events()->attach($event);

        $response = $this->get(route('sponsors.show', $this->sponsor));

        $response->assertOk()
            ->assertSee('Laravel Meetup #1')
            ->assertSee('Past');
    });

    it('displays upcoming events', function (): void {
        $event = Event::factory()->create([
            'title' => 'Future Meetup',
            'starts_at' => now()->addWeek(),
        ]);
        $this->sponsor->events()->attach($event);

        $response = $this->get(route('sponsors.show', $this->sponsor));

        $response->assertOk()
            ->assertSee('Future Meetup')
            ->assertSee('Upcoming');
    });

    it('shows visit website button when website exists', function (): void {
        $response = $this->get(route('sponsors.show', $this->sponsor));

        $response->assertOk()
            ->assertSee('Visit Website');
    });

    it('shows empty state for no events', function (): void {
        $response = $this->get(route('sponsors.show', $this->sponsor));

        $response->assertOk()
            ->assertSee('hasn')
            ->assertSee('sponsored any events yet');
    });

    it('includes back to sponsors link', function (): void {
        $response = $this->get(route('sponsors.show', $this->sponsor));

        $response->assertOk()
            ->assertSee('Back to Sponsors');
    });
});
