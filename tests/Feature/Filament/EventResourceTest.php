<?php

declare(strict_types=1);

use App\Models\OnlineLocation;
use App\Enums\EventLocation;
use App\Enums\PaperStatus;
use App\Enums\RsvpStatus;
use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Resources\Events\Pages\ViewEvent;
use App\Filament\Resources\Events\RelationManagers\AttendeesRelationManager;
use App\Filament\Resources\Events\RelationManagers\PapersRelationManager;
use App\Filament\Resources\Events\RelationManagers\RsvpsRelationManager;
use App\Models\Event;
use App\Models\Paper;
use App\Models\PhysicalLocation;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

it('lists events with search and sorting', function () {
    asAdmin();

    $olderEvent = Event::factory()->create([
        'title' => 'Older event',
        'created_at' => Date::now()->subDays(2),
    ]);

    $newerEvent = Event::factory()->create([
        'title' => 'Newer event',
        'created_at' => Date::now()->subDay(),
    ]);

    Livewire::test(ListEvents::class)
        ->assertCanSeeTableRecords([$olderEvent, $newerEvent])
        ->searchTable('Older event')
        ->assertCanSeeTableRecords([$olderEvent])
        ->assertCanNotSeeTableRecords([$newerEvent]);

    $sortedAsc = Event::query()->oldest()->get();

    Livewire::test(ListEvents::class)
        ->sortTable('created_at', 'asc')
        ->assertCanSeeTableRecords($sortedAsc, inOrder: true);
});

it('supports trashed filtering and bulk actions', function () {
    asAdmin();

    $activeEvent = Event::factory()->create([
        'title' => 'Active event',
    ]);

    $trashedEvent = Event::factory()->create([
        'title' => 'Trashed event',
    ]);
    $trashedEvent->delete();

    Livewire::test(ListEvents::class)
        ->assertTableFilterExists('trashed')
        ->assertCanSeeTableRecords([$activeEvent])
        ->assertCanNotSeeTableRecords([$trashedEvent])
        ->filterTable('trashed', 'with')
        ->assertCanSeeTableRecords([$activeEvent, $trashedEvent])
        ->resetTableFilters()
        ->assertTableBulkActionExists('delete')
        ->assertTableBulkActionExists('forceDelete')
        ->assertTableBulkActionExists('restore')
        ->selectTableRecords([$activeEvent->getKey()])
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertSoftDeleted(Event::class, [
        'id' => $activeEvent->id,
    ]);

    Livewire::test(ListEvents::class)
        ->filterTable('trashed', 'only')
        ->selectTableRecords([$activeEvent->getKey()])
        ->callAction(TestAction::make(RestoreBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertNotSoftDeleted(Event::class, [
        'id' => $activeEvent->id,
    ]);

    Livewire::test(ListEvents::class)
        ->filterTable('trashed', 'only')
        ->selectTableRecords([$trashedEvent->getKey()])
        ->callAction(TestAction::make(ForceDeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertDatabaseMissing(Event::class, [
        'id' => $trashedEvent->id,
    ]);
});

it('renders the create event form and validates required fields', function () {
    asAdmin();

    Livewire::test(CreateEvent::class)
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('type')
        ->assertFormFieldExists('starts_at')
        ->assertFormFieldExists('ends_at')
        ->assertFormSet([
            'type' => EventLocation::Physical->value,
        ])
        ->fillForm([
            'title' => '',
            'type' => EventLocation::Physical->value,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

it('allows creating an event', function () {
    asAdmin();

    $location = PhysicalLocation::factory()->create();

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Test Event',
            'description' => 'Event description',
            'type' => EventLocation::Physical->value,
            'location_type' => PhysicalLocation::class,
            'location_id' => $location->id,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Event::class, [
        'title' => 'Test Event',
        'location_type' => PhysicalLocation::class,
        'location_id' => $location->id,
    ]);
});

it('allows creating an online event with meeting URL', function () {
    asAdmin();

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Test Online Event',
            'description' => 'Online event description',
            'type' => EventLocation::Online->value,
            'online_platform' => 'Zoom',
            'meeting_url' => 'https://zoom.us/j/123456789',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Event::class, [
        'title' => 'Test Online Event',
        'type' => EventLocation::Online->value,
        'location_type' => OnlineLocation::class,
    ]);

    // Check that OnlineLocation was created
    $event = Event::query()->where('title', 'Test Online Event')->first();
    expect($event->location)->toBeInstanceOf(OnlineLocation::class)->and($event->location->platform)->toBe('Zoom')->and($event->location->url)->toBe('https://zoom.us/j/123456789');
});

it('allows creating an online event with just meeting URL (no platform)', function () {
    asAdmin();

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Test Online Event No Platform',
            'description' => 'Online event description',
            'type' => EventLocation::Online->value,
            'meeting_url' => 'https://meet.google.com/abc-defg-hij',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $event = Event::query()->where('title', 'Test Online Event No Platform')->first();
    expect($event->location)->toBeInstanceOf(OnlineLocation::class)->and($event->location->platform)->toBeNull()->and($event->location->url)->toBe('https://meet.google.com/abc-defg-hij');
});

it('renders the edit event form', function () {
    asAdmin();

    $event = Event::factory()->create([
        'title' => 'Original title',
    ]);

    Livewire::test(EditEvent::class, [
        'record' => $event->getKey(),
    ])
        ->assertOk()
        ->assertFormSet([
            'title' => 'Original title',
        ]);
});

it('updates an event title', function () {
    asAdmin();

    $location = PhysicalLocation::factory()->create();

    $event = Event::factory()->create([
        'title' => 'Old title',
        'type' => EventLocation::Physical,
        'location_type' => PhysicalLocation::class,
        'location_id' => $location->id,
    ]);

    Livewire::test(EditEvent::class, [
        'record' => $event->getKey(),
    ])
        ->fillForm([
            'title' => 'New title',
            'type' => EventLocation::Physical->value,
            'location_type' => PhysicalLocation::class,
            'location_id' => $location->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($event->refresh()->title)->toBe('New title');
});

it('edits an online event and loads existing location data', function () {
    asAdmin();

    // Create online location
    $onlineLocation = OnlineLocation::factory()->create([
        'platform' => 'Teams',
        'url' => 'https://teams.microsoft.com/l/meetup-join/...',
    ]);

    $event = Event::factory()->create([
        'title' => 'Online Event',
        'type' => EventLocation::Online,
        'location_type' => OnlineLocation::class,
        'location_id' => $onlineLocation->id,
    ]);

    Livewire::test(EditEvent::class, [
        'record' => $event->getKey(),
    ])
        ->assertOk()
        ->assertFormSet([
            'title' => 'Online Event',
            'online_platform' => 'Teams',
            'meeting_url' => 'https://teams.microsoft.com/l/meetup-join/...',
        ]);
});

it('updates an online event meeting URL', function () {
    asAdmin();

    // Create online location
    $onlineLocation = OnlineLocation::factory()->create([
        'platform' => 'Zoom',
        'url' => 'https://zoom.us/j/123456789',
    ]);

    $event = Event::factory()->create([
        'title' => 'Online Event to Update',
        'type' => EventLocation::Online,
        'location_type' => OnlineLocation::class,
        'location_id' => $onlineLocation->id,
    ]);

    Livewire::test(EditEvent::class, [
        'record' => $event->getKey(),
    ])
        ->fillForm([
            'title' => 'Updated Online Event',
            'online_platform' => 'Google Meet',
            'meeting_url' => 'https://meet.google.com/new-link',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    // Check that the event was updated
    expect($event->refresh()->title)->toBe('Updated Online Event');

    // Check that the location was updated
    expect($onlineLocation->refresh()->platform)->toBe('Google Meet');
    expect($onlineLocation->refresh()->url)->toBe('https://meet.google.com/new-link');
});

it('renders the view event page with infolist', function () {
    asAdmin();

    $event = Event::factory()->create([
        'title' => 'Viewable event',
        'type' => EventLocation::Physical,
    ]);

    Livewire::test(ViewEvent::class, [
        'record' => $event->getKey(),
    ])
        ->assertOk()
        ->assertSee('Viewable event')
        ->assertSee('In Person');
});

it('displays physical location details in event view', function () {
    asAdmin();

    $location = PhysicalLocation::factory()->create([
        'venue_name' => 'Test Venue',
        'address' => '123 Main St',
        'city' => 'Test City',
    ]);

    $event = Event::factory()->create([
        'title' => 'Physical Event',
        'type' => EventLocation::Physical,
        'location_type' => PhysicalLocation::class,
        'location_id' => $location->id,
    ]);

    Livewire::test(ViewEvent::class, [
        'record' => $event->getKey(),
    ])
        ->assertOk()
        ->assertSee('Test Venue')
        ->assertSee('123 Main St')
        ->assertSee('Test City');
});

it('displays online location details in event view', function () {
    asAdmin();

    $onlineLocation = OnlineLocation::factory()->create([
        'platform' => 'Zoom',
        'url' => 'https://zoom.us/j/123456789',
    ]);

    $event = Event::factory()->create([
        'title' => 'Online Event',
        'type' => EventLocation::Online,
        'location_type' => OnlineLocation::class,
        'location_id' => $onlineLocation->id,
    ]);

    Livewire::test(ViewEvent::class, [
        'record' => $event->getKey(),
    ])
        ->assertOk()
        ->assertSee('Zoom')
        ->assertSee('https://zoom.us/j/123456789');
});

it('uses actions for online event creation through form', function () {
    asAdmin();

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Test Online Event via Action',
            'description' => 'Online event description',
            'type' => EventLocation::Online->value,
            'online_platform' => 'Teams',
            'meeting_url' => 'https://teams.microsoft.com/l/meetup-join/123',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    // Verify the event was created with correct data
    assertDatabaseHas(Event::class, [
        'title' => 'Test Online Event via Action',
        'type' => EventLocation::Online->value,
    ]);

    // Verify that the OnlineLocation was created via the action
    $event = Event::query()->where('title', 'Test Online Event via Action')->first();
    expect($event->location)->toBeInstanceOf(OnlineLocation::class)->and($event->location->platform)->toBe('Teams')->and($event->location->url)->toBe('https://teams.microsoft.com/l/meetup-join/123');
});

it('uses actions for physical event creation through form', function () {
    asAdmin();

    $location = PhysicalLocation::factory()->create();

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Test Physical Event via Action',
            'description' => 'Physical event description',
            'type' => EventLocation::Physical->value,
            'location_type' => PhysicalLocation::class,
            'location_id' => $location->id,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    // Verify the event was created with correct data
    assertDatabaseHas(Event::class, [
        'title' => 'Test Physical Event via Action',
        'type' => EventLocation::Physical->value,
        'location_type' => PhysicalLocation::class,
        'location_id' => $location->id,
    ]);
});

it('renders the attendees relation manager table', function () {
    asAdmin();

    $user = User::factory()->create([
        'name' => 'John Attendee',
        'email' => 'john@example.com',
    ]);

    $event = Event::factory()->create();
    $event->attendees()->attach($user, ['status' => RsvpStatus::Going->value]);

    Livewire::test(AttendeesRelationManager::class, [
        'ownerRecord' => $event,
        'pageClass' => ViewEvent::class,
    ])
        ->assertCanSeeTableRecords([$user])
        ->searchTable('John')
        ->assertCanSeeTableRecords([$user]);
});

it('renders the rsvps relation manager table with status badges', function () {
    asAdmin();

    $user = User::factory()->create([
        'name' => 'Jane Rsvp',
        'email' => 'jane@example.com',
    ]);

    $event = Event::factory()->create();
    $event->attendees()->attach($user, ['status' => RsvpStatus::Maybe->value]);

    Livewire::test(RsvpsRelationManager::class, [
        'ownerRecord' => $event,
        'pageClass' => ViewEvent::class,
    ])
        ->assertCanSeeTableRecords([$user])
        ->assertSee('Jane Rsvp')
        ->assertSee(RsvpStatus::Maybe->label());
});

it('renders the papers relation manager table', function () {
    asAdmin();

    $event = Event::factory()->create();
    $speaker = User::factory()->create();

    $paper = Paper::factory()->create([
        'event_id' => $event->id,
        'user_id' => $speaker->id,
        'title' => 'Test paper',
        'status' => PaperStatus::Approved,
    ]);

    Livewire::test(PapersRelationManager::class, [
        'ownerRecord' => $event,
        'pageClass' => ViewEvent::class,
    ])
        ->assertCanSeeTableRecords([$paper])
        ->searchTable('Test paper')
        ->assertCanSeeTableRecords([$paper]);
});

it('displays event with attendees and papers', function () {
    asAdmin();

    $event = Event::factory()->create([
        'title' => 'Event with attendees',
    ]);
    $user = User::factory()->create();
    $speaker = User::factory()->create();

    // Attach attendees
    $event->attendees()->attach($user, ['status' => RsvpStatus::Going->value]);

    // Create papers
    Paper::factory()->create([
        'event_id' => $event->id,
        'user_id' => $speaker->id,
        'status' => PaperStatus::Approved,
    ]);

    Livewire::test(ListEvents::class)
        ->searchTable('Event with attendees')
        ->assertCanSeeTableRecords([$event]);
});
