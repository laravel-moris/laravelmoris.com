<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Paper\MovePaperToEventAction;
use App\Actions\Paper\UpdatePaperStatusAction;
use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\Paper;
use App\Queries\GetPapersQuery;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

class ManagePapersCommand extends Command
{
    protected $signature = 'papers:manage';

    protected $description = 'Interactively manage papers';

    public function handle(
        GetPapersQuery $getPapersQuery,
        UpdatePaperStatusAction $updateStatusAction,
        MovePaperToEventAction $movePaperAction,
    ): int {
        while (true) {
            $this->clearScreen();

            $choice = select(
                label: 'Paper Management',
                options: [
                    'view' => 'View Papers',
                    'exit' => 'Exit',
                ],
            );

            if ($choice === 'exit') {
                $this->clearScreen();
                info('Goodbye.');

                return self::SUCCESS;
            }

            $this->papersFlow($getPapersQuery, $updateStatusAction, $movePaperAction);
        }
    }

    /**
     * Papers list + selection "screen"
     */
    private function papersFlow(
        GetPapersQuery $query,
        UpdatePaperStatusAction $updateStatus,
        MovePaperToEventAction $movePaper,
    ): void {
        $this->clearScreen();

        $papers = spin(
            fn () => $query->execute(),
            'Loading papers...',
        );

        if ($papers->isEmpty()) {
            warning('No papers found.');

            select(
                label: 'What next?',
                options: [
                    'back' => 'Back',
                ],
            );

            return;
        }

        $this->renderPapersTable($papers->take(20));

        if ($papers->count() > 20) {
            info('');
            info('Showing first 20 results. Use search to narrow down.');
        }

        info('');
        $paper = $this->selectPaper($papers);

        if (! $paper) {
            return;
        }

        $this->paperFlow($paper, $updateStatus, $movePaper);
    }

    /**
     * Single paper detail + actions "screen"
     */
    private function paperFlow(
        Paper $paper,
        UpdatePaperStatusAction $updateStatus,
        MovePaperToEventAction $movePaper,
    ): void {
        while (true) {
            $paper->refresh();

            $this->clearScreen();
            $this->renderPaperVerticalTable($paper);

            $action = select(
                label: 'Actions',
                options: [
                    'status' => 'Change Status',
                    'move' => 'Move to Event',
                    'back' => 'Back',
                ],
            );

            if ($action === 'back') {
                return;
            }

            if ($action === 'status') {
                $this->clearScreen();
                $this->renderPaperVerticalTable($paper);
                $this->changeStatus($paper, $updateStatus);

                continue;
            }

            if ($action === 'move') {
                $this->clearScreen();
                $this->renderPaperVerticalTable($paper);
                $this->moveEvent($paper, $movePaper);

                continue;
            }
        }
    }

    /**
     * Vertical key/value table for paper details.
     */
    private function renderPaperVerticalTable(Paper $paper): void
    {
        table(
            headers: ['Field', 'Value'],
            rows: [
                ['ID', (string) $paper->id],
                ['Title', $paper->title],
                ['Description', wordwrap($paper->description ?? 'No description provided.', 80, "\n", true)],
                ['Contact Number', $paper->speaker->phone ?? 'No contact number provided.'],
                ['Contact Email', $paper->speaker->email ?? 'No contact email provided.'],
                ['Speaker', $paper->speaker->name ?? 'N/A'],
                ['Status', $paper->status->value],
                ['Event', $paper->event->title ?? 'N/A'],
            ],
        );

        info('');
    }

    private function changeStatus(Paper $paper, UpdatePaperStatusAction $action): void
    {
        $current = $paper->status;

        $options = [];
        foreach (PaperStatus::cases() as $case) {
            $options[$case->value] = $case === $current
                ? "{$case->value} (current)"
                : $case->value;
        }

        $selected = select(
            label: 'Select new status',
            options: $options,
            default: $current->value,
        );

        if ($selected === $current->value) {
            warning('Status unchanged.');

            return;
        }

        if (! confirm("Change status to '{$selected}'?", true)) {
            warning('Cancelled.');

            return;
        }

        try {
            $action->execute($paper, PaperStatus::from($selected));
            info('Status updated.');
        } catch (\Throwable $e) {
            error($e->getMessage());
        }
    }

    private function moveEvent(Paper $paper, MovePaperToEventAction $action): void
    {
        $events = spin(
            fn () => Event::query()->latest('starts_at')->get(),
            'Loading events...',
        );

        if ($events->isEmpty()) {
            warning('No events available.');

            return;
        }

        $options = [];
        foreach ($events as $event) {
            if ($paper->event && $event->id === $paper->event->id) {
                continue;
            }

            $options[(string) $event->id] = sprintf(
                '%s (%s)',
                $event->title,
                $event->starts_at->format('Y-m-d'),
            );
        }

        if ($options === []) {
            warning('No other events available.');

            return;
        }

        $selectedId = select(
            label: 'Move to which event?',
            options: $options,
            scroll: 10,
        );

        $event = $events->firstWhere('id', (int) $selectedId);

        if (! $event) {
            error('Event not found.');

            return;
        }

        if (! confirm("Move to '{$event->title}'?", true)) {
            warning('Cancelled.');

            return;
        }

        try {
            $action->execute($paper, $event);
            info('Paper moved.');
        } catch (\Throwable $e) {
            error($e->getMessage());
        }
    }

    private function renderPapersTable(Collection $papers): void
    {
        $rows = $papers->map(fn (Paper $p) => [
            (string) $p->id,
            str($p->title)->limit(40)->toString(),
            $p->speaker->name ?? 'N/A',
            $p->event->title ?? 'N/A',
            $p->status->value,
        ])->all();

        table(
            headers: ['ID', 'Title', 'Speaker', 'Event', 'Status'],
            rows: $rows,
        );
    }

    private function selectPaper(Collection $papers): ?Paper
    {
        $map = $papers->keyBy('id');

        $paperOptions = $papers->mapWithKeys(fn (Paper $p) => [
            (string) $p->id => "[{$p->id}] {$p->title} - ".($p->speaker->name ?? 'N/A'),
        ])->all();

        if ($paperOptions === []) {
            warning('No papers available.');

            return null;
        }

        $id = select(
            label: 'Select a paper',
            options: $paperOptions,
            scroll: 10,
        );

        return $map->get((int) $id);
    }

    /**
     * Clear terminal screen
     */
    private function clearScreen(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
    }
}
