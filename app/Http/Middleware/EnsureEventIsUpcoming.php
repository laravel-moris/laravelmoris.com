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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Event|null $event */
        $event = $request->route('event');

        if ($event instanceof Event && $event->ends_at?->isPast()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You cannot submit a talk for past events.');
        }

        return $next($request);
    }
}
