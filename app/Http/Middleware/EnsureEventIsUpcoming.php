<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Event;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class EnsureEventIsUpcoming
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Event|null $event */
        $event = $request->route('event');

        if ($event instanceof Event && $event->starts_at?->isPast()) {
            return to_route('events.show', $event)
                ->with('error', 'This event has already started.');
        }

        return $next($request);
    }
}
