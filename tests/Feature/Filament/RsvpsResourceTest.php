<?php

declare(strict_types=1);

use App\Enums\RsvpStatus;
use App\Filament\Resources\Rsvps\Pages\CreateRsvp;
use App\Filament\Resources\Rsvps\Pages\EditRsvp;
use App\Filament\Resources\Rsvps\Pages\ListRsvps;
use App\Filament\Resources\Rsvps\Pages\ViewRsvp;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('lists rsvps with search, filters, sorting, and bulk delete', function () {
    asAdmin();

    $eventA = Event::factory()->create([
        'title' => 'Event A',
    ]);
    $eventB = Event::factory()->create([
        'title' => 'Event B',
    ]);

    $userA = User::factory()->create([
        'name' => 'Alice Attendee',
    ]);
    $userB = User::factory()->create([
        'name' => 'Bob Attendee',
    ]);

    $userA->rsvps()->attach($eventA->id, [
        'status' => RsvpStatus::Going->value,
    ]);
    $userB->rsvps()->attach($eventB->id, [
        'status' => RsvpStatus::Maybe->value,
    ]);

    $rsvpA = Rsvp::query()
        ->where('user_id', $userA->id)
        ->where('event_id', $eventA->id)
        ->firstOrFail();

    $rsvpB = Rsvp::query()
        ->where('user_id', $userB->id)
        ->where('event_id', $eventB->id)
        ->firstOrFail();

    Livewire::test(ListRsvps::class)
        ->assertCanSeeTableRecords([$rsvpA, $rsvpB])
        ->searchTable('Alice')
        ->assertCanSeeTableRecords([$rsvpA])
        ->assertCanNotSeeTableRecords([$rsvpB]);

    Livewire::test(ListRsvps::class)
        ->assertTableFilterExists('status')
        ->filterTable('status', RsvpStatus::Going->value)
        ->assertCanSeeTableRecords([$rsvpA])
        ->assertCanNotSeeTableRecords([$rsvpB]);

    Livewire::test(ListRsvps::class)
        ->assertTableFilterExists('event')
        ->filterTable('event', $eventA->id)
        ->assertCanSeeTableRecords([$rsvpA])
        ->assertCanNotSeeTableRecords([$rsvpB]);

    Livewire::test(ListRsvps::class)
        ->assertTableFilterExists('user')
        ->filterTable('user', $userB->id)
        ->assertCanSeeTableRecords([$rsvpB])
        ->assertCanNotSeeTableRecords([$rsvpA]);

    $sortedAsc = Rsvp::query()
        ->orderBy(
            User::query()
                ->select('name')
                ->whereColumn('users.id', 'event_user.user_id')
        )
        ->get();

    Livewire::test(ListRsvps::class)
        ->sortTable('user.name', 'asc')
        ->assertCanSeeTableRecords($sortedAsc, inOrder: true)
        ->assertTableBulkActionExists('delete')
        ->selectTableRecords([$rsvpB->getKey()])
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertDatabaseMissing('event_user', [
        'id' => $rsvpB->getKey(),
    ]);
});

it('renders the create rsvp form and validates required fields', function () {
    asAdmin();

    Livewire::test(CreateRsvp::class)
        ->assertFormFieldExists('event_id')
        ->assertFormFieldExists('user_id')
        ->assertFormFieldExists('status')
        ->fillForm([
            'event_id' => null,
            'user_id' => null,
            'status' => RsvpStatus::Maybe->value,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'event_id' => 'required',
            'user_id' => 'required',
        ]);
});

it('allows creating an rsvp', function () {
    asAdmin();

    $event = Event::factory()->create();
    $user = User::factory()->create();

    Livewire::test(CreateRsvp::class)
        ->fillForm([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => RsvpStatus::Maybe->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('event_user', [
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => RsvpStatus::Maybe->value,
    ]);
});

it('disables event and user fields on the edit rsvp form', function () {
    asAdmin();

    $event = Event::factory()->create();
    $user = User::factory()->create();

    $user->rsvps()->attach($event->id, [
        'status' => RsvpStatus::Maybe->value,
    ]);

    $rsvp = Rsvp::query()
        ->where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->firstOrFail();

    Livewire::test(EditRsvp::class, [
        'record' => $rsvp->id,
    ])
        ->assertOk()
        ->assertFormFieldDisabled('event_id')
        ->assertFormFieldDisabled('user_id');
});

it('updates rsvp status via the edit rsvp page', function () {
    asAdmin();

    $event = Event::factory()->create();
    $user = User::factory()->create();

    $user->rsvps()->attach($event->id, [
        'status' => RsvpStatus::Maybe->value,
    ]);

    $rsvp = Rsvp::query()
        ->where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->firstOrFail();

    Livewire::test(EditRsvp::class, [
        'record' => $rsvp->id,
    ])
        ->set('data.status', RsvpStatus::Going->value)
        ->call('save', false)
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('event_user', [
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => RsvpStatus::Going->value,
    ]);
});

it('deletes an rsvp from the edit page', function () {
    asAdmin();

    $event = Event::factory()->create();
    $user = User::factory()->create();

    $user->rsvps()->attach($event->id, [
        'status' => RsvpStatus::Maybe->value,
    ]);

    $rsvp = Rsvp::query()
        ->where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->firstOrFail();

    Livewire::test(EditRsvp::class, [
        'record' => $rsvp->id,
    ])
        ->mountAction('delete')
        ->assertMountedActionModalSee('Delete')
        ->callMountedAction()
        ->assertNotified();

    assertDatabaseMissing('event_user', [
        'user_id' => $user->id,
        'event_id' => $event->id,
    ]);
});

it('renders the rsvp view page infolist', function () {
    asAdmin();

    $event = Event::factory()->create([
        'title' => 'My Event',
    ]);
    $user = User::factory()->create([
        'name' => 'My User',
    ]);

    $user->rsvps()->attach($event->id, [
        'status' => RsvpStatus::Going->value,
    ]);

    $rsvp = Rsvp::query()
        ->where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->firstOrFail();

    Livewire::test(ViewRsvp::class, [
        'record' => $rsvp->id,
    ])
        ->assertOk()
        ->assertSee('My Event')
        ->assertSee('My User')
        ->assertSee(RsvpStatus::Going->label());
});
