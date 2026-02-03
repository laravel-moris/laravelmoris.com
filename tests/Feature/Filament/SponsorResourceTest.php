<?php

declare(strict_types=1);

use App\Filament\Resources\Events\RelationManagers\SponsorsRelationManager;
use App\Filament\Resources\Sponsors\Pages\CreateSponsor;
use App\Filament\Resources\Sponsors\Pages\EditSponsor;
use App\Filament\Resources\Sponsors\Pages\ListSponsors;
use App\Filament\Resources\Sponsors\Pages\ViewSponsor;
use App\Models\Event;
use App\Models\Sponsor;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('lists sponsors with search and sorting', function () {
    asAdmin();

    $olderSponsor = Sponsor::factory()->create([
        'name' => 'Older sponsor',
        'created_at' => Date::now()->subDays(2),
    ]);

    $newerSponsor = Sponsor::factory()->create([
        'name' => 'Newer sponsor',
        'created_at' => Date::now()->subDay(),
    ]);

    Livewire::test(ListSponsors::class)
        ->assertCanSeeTableRecords([$olderSponsor, $newerSponsor])
        ->searchTable('Older sponsor')
        ->assertCanSeeTableRecords([$olderSponsor])
        ->assertCanNotSeeTableRecords([$newerSponsor]);

    $sortedAsc = Sponsor::query()->oldest()->get();

    Livewire::test(ListSponsors::class)
        ->sortTable('created_at', 'asc')
        ->assertCanSeeTableRecords($sortedAsc, inOrder: true);
});

it('supports bulk delete action', function () {
    asAdmin();

    $sponsor = Sponsor::factory()->create([
        'name' => 'Sponsor to delete',
    ]);

    Livewire::test(ListSponsors::class)
        ->assertActionExists(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->selectTableRecords([$sponsor->getKey()])
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    assertDatabaseMissing(Sponsor::class, [
        'id' => $sponsor->id,
    ]);
});

it('renders the create sponsor form and validates required fields', function () {
    asAdmin();

    Livewire::test(CreateSponsor::class)
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('website')
        ->assertFormFieldExists('logo')
        ->fillForm([
            'name' => '',
            'website' => 'not-a-valid-url',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'website' => 'url']);
});

it('allows creating a sponsor', function () {
    asAdmin();

    Storage::fake('public');

    Livewire::test(CreateSponsor::class)
        ->fillForm([
            'name' => 'Test Sponsor',
            'website' => 'https://example.com',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Sponsor::class, [
        'name' => 'Test Sponsor',
        'website' => 'https://example.com',
    ]);
});

it('renders the edit sponsor form', function () {
    asAdmin();

    $sponsor = Sponsor::factory()->create([
        'name' => 'Original name',
    ]);

    Livewire::test(EditSponsor::class, [
        'record' => $sponsor->getKey(),
    ])
        ->assertOk()
        ->assertFormSet([
            'name' => 'Original name',
        ]);
});

it('updates a sponsor name', function () {
    asAdmin();

    $sponsor = Sponsor::factory()->create([
        'name' => 'Old name',
    ]);

    Livewire::test(EditSponsor::class, [
        'record' => $sponsor->getKey(),
    ])
        ->fillForm([
            'name' => 'New name',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($sponsor->refresh()->name)->toBe('New name');
});

it('renders the view sponsor page with infolist', function () {
    asAdmin();

    $sponsor = Sponsor::factory()->create([
        'name' => 'Viewable sponsor',
        'website' => 'https://example.com',
    ]);

    Livewire::test(ViewSponsor::class, [
        'record' => $sponsor->getKey(),
    ])
        ->assertOk()
        ->assertSee('Viewable sponsor')
        ->assertSee('https://example.com');
});

it('renders the sponsors relation manager table on events', function () {
    asAdmin();

    $sponsor = Sponsor::factory()->create([
        'name' => 'Event Sponsor',
    ]);

    $event = Event::factory()->create();
    $event->sponsors()->attach($sponsor);

    Livewire::test(SponsorsRelationManager::class, [
        'ownerRecord' => $event,
        'pageClass' => \App\Filament\Resources\Events\Pages\ViewEvent::class,
    ])
        ->assertCanSeeTableRecords([$sponsor])
        ->searchTable('Event Sponsor')
        ->assertCanSeeTableRecords([$sponsor]);
});
