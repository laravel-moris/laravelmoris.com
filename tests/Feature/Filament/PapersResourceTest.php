<?php

declare(strict_types=1);

use App\Enums\PaperStatus;
use App\Filament\Resources\Papers\Pages\CreatePaper;
use App\Filament\Resources\Papers\Pages\EditPaper;
use App\Filament\Resources\Papers\Pages\ListPapers;
use App\Filament\Resources\Papers\Pages\ViewPaper;
use App\Filament\Resources\Papers\RelationManagers\SpeakerRelationManager;
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

it('lists papers with search and sorting', function () {
    asAdmin();

    $olderPaper = Paper::factory()->create([
        'title' => 'Older paper',
        'created_at' => Date::now()->subDays(2),
    ]);

    $newerPaper = Paper::factory()->create([
        'title' => 'Newer paper',
        'created_at' => Date::now()->subDay(),
    ]);

    Livewire::test(ListPapers::class)
        ->assertCanSeeTableRecords([$olderPaper, $newerPaper])
        ->searchTable('Older paper')
        ->assertCanSeeTableRecords([$olderPaper])
        ->assertCanNotSeeTableRecords([$newerPaper]);

    $sortedAsc = Paper::query()->oldest()->get();

    Livewire::test(ListPapers::class)
        ->sortTable('created_at', 'asc')
        ->assertCanSeeTableRecords($sortedAsc, inOrder: true);
});

it('supports trashed filtering and bulk actions', function () {
    asAdmin();

    $activePaper = Paper::factory()->create([
        'title' => 'Active paper',
    ]);

    $trashedPaper = Paper::factory()->create([
        'title' => 'Trashed paper',
    ]);
    $trashedPaper->delete();

    Livewire::test(ListPapers::class)
        ->assertTableFilterExists('trashed')
        ->assertCanSeeTableRecords([$activePaper])
        ->assertCanNotSeeTableRecords([$trashedPaper])
        ->filterTable('trashed', 'with')
        ->assertCanSeeTableRecords([$activePaper, $trashedPaper])
        ->resetTableFilters()
        ->assertTableBulkActionExists('delete')
        ->assertTableBulkActionExists('forceDelete')
        ->assertTableBulkActionExists('restore')
        ->selectTableRecords([$activePaper->getKey()])
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertSoftDeleted(Paper::class, [
        'id' => $activePaper->id,
    ]);

    Livewire::test(ListPapers::class)
        ->filterTable('trashed', 'only')
        ->selectTableRecords([$activePaper->getKey()])
        ->callAction(TestAction::make(RestoreBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertNotSoftDeleted(Paper::class, [
        'id' => $activePaper->id,
    ]);

    Livewire::test(ListPapers::class)
        ->filterTable('trashed', 'only')
        ->selectTableRecords([$trashedPaper->getKey()])
        ->callAction(TestAction::make(ForceDeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertDatabaseMissing(Paper::class, [
        'id' => $trashedPaper->id,
    ]);
});

it('renders the create paper form and validates required fields', function () {
    asAdmin();

    $event = Event::factory()->create();
    $speaker = User::factory()->create();

    Livewire::test(CreatePaper::class)
        ->assertFormFieldExists('user_id')
        ->assertFormFieldExists('event_id')
        ->assertFormFieldExists('status')
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormSet([
            'status' => PaperStatus::Draft->value,
        ])
        ->fillForm([
            'user_id' => $speaker->id,
            'event_id' => $event->id,
            'status' => PaperStatus::Draft->value,
            'title' => '',
            'description' => 'A description',
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

it('allows creating a paper', function () {
    asAdmin();

    $event = Event::factory()->create();
    $speaker = User::factory()->create();

    Livewire::test(CreatePaper::class)
        ->fillForm([
            'user_id' => $speaker->id,
            'event_id' => $event->id,
            'status' => PaperStatus::Draft->value,
            'title' => 'My talk',
            'description' => 'Talk description',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Paper::class, [
        'title' => 'My talk',
    ]);
});

it('renders the edit paper form', function () {
    asAdmin();

    $paper = Paper::factory()->create([
        'title' => 'Original title',
    ]);

    Livewire::test(EditPaper::class, [
        'record' => $paper->getKey(),
    ])
        ->assertOk()
        ->assertFormSet([
            'title' => 'Original title',
        ]);
});

it('updates paper status via the view page action', function () {
    asAdmin();

    $paper = Paper::factory()->create([
        'status' => PaperStatus::Draft,
    ]);

    Livewire::test(ViewPaper::class, [
        'record' => $paper->getKey(),
    ])
        ->assertActionExists('changeStatus')
        ->mountAction('changeStatus')
        ->assertMountedActionModalSee('Change status')
        ->assertSchemaStateSet([
            'status' => PaperStatus::Draft->value,
        ])
        ->fillForm([
            'status' => null,
        ])
        ->callMountedAction()
        ->assertHasFormErrors(['status' => 'required'])
        ->fillForm([
            'status' => PaperStatus::Approved->value,
        ])
        ->callMountedAction()
        ->assertHasNoFormErrors()
        ->assertNotified('Paper status updated');

    expect($paper->refresh()->status)->toBe(PaperStatus::Approved);
});

it('moves a paper to another event via the view page action', function () {
    asAdmin();

    $fromEvent = Event::factory()->create();
    $toEvent = Event::factory()->create();

    $paper = Paper::factory()->create([
        'event_id' => $fromEvent->id,
    ]);

    Livewire::test(ViewPaper::class, [
        'record' => $paper->getKey(),
    ])
        ->assertActionExists('moveToEvent')
        ->mountAction('moveToEvent')
        ->assertMountedActionModalSee('Move to event')
        ->assertSchemaStateSet([
            'event_id' => $fromEvent->id,
        ])
        ->fillForm([
            'event_id' => null,
        ])
        ->callMountedAction()
        ->assertHasFormErrors(['event_id' => 'required'])
        ->fillForm([
            'event_id' => $toEvent->id,
        ])
        ->callMountedAction()
        ->assertHasNoFormErrors()
        ->assertNotified('Paper moved to event');

    expect($paper->refresh()->event_id)->toBe($toEvent->id);
});

it('renders the speaker relation manager table', function () {
    asAdmin();

    $speaker = User::factory()->create([
        'name' => 'Jane Speaker',
        'email' => 'jane@example.com',
    ]);

    $paper = Paper::factory()->create([
        'user_id' => $speaker->id,
    ]);

    Livewire::test(SpeakerRelationManager::class, [
        'ownerRecord' => $paper,
        'pageClass' => ViewPaper::class,
    ])
        ->assertCanSeeTableRecords([$speaker])
        ->searchTable('Jane')
        ->assertCanSeeTableRecords([$speaker]);
});
