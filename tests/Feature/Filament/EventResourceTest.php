<?php

declare(strict_types=1);

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

    Livewire::test(CreateEvent::class)
        ->fillForm([
            'title' => 'Test Event',
            'description' => 'Event description',
            'type' => EventLocation::Physical->value,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Event::class, [
        'title' => 'Test Event',
    ]);
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

    $event = Event::factory()->create([
        'title' => 'Old title',
    ]);

    Livewire::test(EditEvent::class, [
        'record' => $event->getKey(),
    ])
        ->fillForm([
            'title' => 'New title',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($event->refresh()->title)->toBe('New title');
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
