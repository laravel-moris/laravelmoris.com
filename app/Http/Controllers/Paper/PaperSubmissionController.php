<?php

declare(strict_types=1);

namespace App\Http\Controllers\Paper;

use App\Actions\Paper\SubmitPaperAction;
use App\Data\Paper\SubmitPaperData;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

final readonly class PaperSubmissionController
{
    public function create(Event $event): View
    {
        return view('pages.papers.create', [
            'event' => $event,
        ]);
    }

    public function store(Event $event, Request $request, SubmitPaperAction $submitPaperAction): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            return to_route('login');
        }

        if ($event->starts_at->isPast()) {
            Session::flash('error', 'You cannot submit a talk for past events.');

            return to_route('events.show', $event);
        }

        $data = SubmitPaperData::from($request->all());

        $submitPaperAction->execute($user, $event, $data);

        Session::flash('success', 'Your talk has been submitted successfully!');

        return to_route('events.show', $event);
    }
}
