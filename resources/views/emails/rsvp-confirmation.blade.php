<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Confirmation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f5;
            color: #18181b;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 24px;
        }

        .header {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 12px 12px 0 0;
            padding: 32px 24px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .body {
            background: #ffffff;
            border-radius: 0 0 12px 12px;
            padding: 32px 24px;
        }

        .badge {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 9999px;
            margin-bottom: 16px;
        }

        h2 {
            font-size: 20px;
            color: #18181b;
            margin: 0 0 24px 0;
        }

        .details {
            border-top: 1px solid #e4e4e7;
            padding-top: 24px;
            margin-top: 24px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 12px;
        }

        .detail-label {
            width: 80px;
            color: #71717a;
            font-size: 14px;
        }

        .detail-value {
            color: #18181b;
            font-size: 14px;
            font-weight: 500;
        }

        .button {
            display: inline-block;
            background: #6366f1;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 24px;
        }

        .reminder {
            background: #f4f4f5;
            border-radius: 8px;
            padding: 16px;
            margin-top: 24px;
            font-size: 13px;
            color: #52525b;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #a1a1aa;
        }

        .footer a {
            color: #6366f1;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="body">
            <div class="badge">You're in!</div>
            <h2>See you at {{ $event->title }}</h2>

            <p style="color: #52525b; font-size: 15px; line-height: 1.6;">Hi {{ $user->name }}, your RSVP has been
                confirmed. We're looking forward to seeing you there!</p>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Event</span>
                    <span class="detail-value">{{ $event->title }}</span>
                </div>
                @if($event->starts_at)
                    <div class="detail-row">
                        <span class="detail-label">Date</span>
                        <span class="detail-value">{{ $event->starts_at->format('l, F j, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Time</span>
                        <span
                            class="detail-value">{{ $event->starts_at->format('g:i A') }}{{ $event->ends_at ? ' - ' . $event->ends_at->format('g:i A') : '' }}</span>
                    </div>
                @endif
                @if($event->location)
                    <div class="detail-row">
                        <span class="detail-label">Location</span>
                        <span class="detail-value">{{ $event->location->name ?? 'TBD' }}</span>
                    </div>
                @endif
            </div>

            <a href="{{ url('/') }}" class="button">View Event Details</a>

            <div class="reminder">
                <strong>Can't make it?</strong> No worries! If your plans change, you can update your RSVP anytime —
                just visit the event page. We appreciate the heads-up so we can plan accordingly.
            </div>
        </div>
        <div class="footer">
            <p>{{ config('app.name') }} &mdash; A Laravel Community</p>
            <p>
                <a href="{{ url('/') }}">Website</a> &bull;
                <a href="https://github.com/laravel-moris">GitHub</a>
            </p>
            <p>If you didn't RSVP to this event, you can safely ignore this email.</p>
        </div>
    </div>
</body>

</html>
