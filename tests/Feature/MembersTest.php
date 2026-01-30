<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\Paper;
use App\Models\User;

beforeEach(function (): void {
    $this->member = User::factory()->create([
        'name' => 'John Doe',
        'title' => 'Senior Developer',
        'bio' => 'A passionate Laravel developer',
    ]);
});

describe('members index', function (): void {
    it('displays the members listing page', function (): void {
        $response = $this->get(route('members.index'));

        $response->assertOk()
            ->assertViewIs('pages.members.index')
            ->assertViewHas('members')
            ->assertSee('Our Community Members')
            ->assertSee($this->member->name);
    });

    it('displays member statistics on cards', function (): void {
        $event = Event::factory()->create();
        $this->member->rsvps()->attach($event, ['status' => 'confirmed']);

        Paper::factory()->create([
            'user_id' => $this->member->id,
            'event_id' => $event->id,
            'status' => 'approved',
        ]);

        $response = $this->get(route('members.index'));

        $response->assertOk()
            ->assertSee('1')
            ->assertSee('1');
    });

    it('paginates members', function (): void {
        User::factory()->count(15)->create();

        $response = $this->get(route('members.index'));

        $response->assertOk()
            ->assertViewHas('members', function ($members) {
                return $members->count() === 12; // Default pagination
            });
    });

    it('displays empty state when no members exist', function (): void {
        User::query()->delete();

        $response = $this->get(route('members.index'));

        $response->assertOk()
            ->assertSee('No Members Yet')
            ->assertSee('Join Community');
    });
});

describe('member show', function (): void {
    it('displays a member profile', function (): void {
        $response = $this->get(route('members.show', $this->member));

        $response->assertOk()
            ->assertViewIs('pages.members.show')
            ->assertViewHas('member')
            ->assertSee($this->member->name)
            ->assertSee($this->member->title)
            ->assertSee($this->member->bio)
            ->assertSee('Speaking History')
            ->assertSee('Attendance History');
    });

    it('displays member speaking history', function (): void {
        $event = Event::factory()->create([
            'title' => 'Laravel Meetup #1',
            'starts_at' => now()->subMonth(),
        ]);

        Paper::factory()->create([
            'user_id' => $this->member->id,
            'event_id' => $event->id,
            'title' => 'Advanced Eloquent',
            'description' => 'Deep dive into Eloquent ORM',
            'status' => 'approved',
        ]);

        $response = $this->get(route('members.show', $this->member));

        $response->assertOk()
            ->assertSee('Advanced Eloquent')
            ->assertSee('Laravel Meetup #1')
            ->assertSee('Deep dive into Eloquent ORM')
            ->assertSee('Speaker');
    });

    it('displays member attendance history', function (): void {
        $event = Event::factory()->create([
            'title' => 'Laravel Workshop',
            'starts_at' => now()->subWeek(),
        ]);

        $this->member->rsvps()->attach($event, ['status' => 'confirmed']);

        $response = $this->get(route('members.show', $this->member));

        $response->assertOk()
            ->assertSee('Laravel Workshop')
            ->assertSee('Attended');
    });

    it('displays upcoming events as confirmed', function (): void {
        $event = Event::factory()->create([
            'title' => 'Upcoming Meetup',
            'starts_at' => now()->addWeek(),
        ]);

        $this->member->rsvps()->attach($event, ['status' => 'confirmed']);

        $response = $this->get(route('members.show', $this->member));

        $response->assertOk()
            ->assertSee('Upcoming Meetup')
            ->assertSee('confirmed');
    });

    it('shows empty state for no speaking history', function (): void {
        $response = $this->get(route('members.show', $this->member));

        $response->assertOk()
            ->assertSee('Speaking History')
            ->assertSee('hasn')
            ->assertSee('spoken at any events yet');
    });

    it('shows empty state for no attendance history', function (): void {
        $response = $this->get(route('members.show', $this->member));

        $response->assertOk()
            ->assertSee('Attendance History')
            ->assertSee('hasn')
            ->assertSee('attended any events yet');
    });

    it('includes back to members link', function (): void {
        $response = $this->get(route('members.show', $this->member));

        $response->assertOk()
            ->assertSee('Back to Members');
    });
});
