<?php

declare(strict_types=1);

use App\Actions\Paper\SubmitPaperAction;
use App\Http\Controllers\Paper\PaperSubmissionController;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

test('controller store redirects to login when no user is authenticated', function () {
    auth()->logout();

    $event = Event::factory()->create();

    $response = app(PaperSubmissionController::class)->store(
        event: $event,
        request: Request::create('/events/'.$event->id.'/submit-paper', 'POST'),
        submitPaperAction: app(SubmitPaperAction::class),
    );

    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->getTargetUrl())->toBe(route('login'));
});
