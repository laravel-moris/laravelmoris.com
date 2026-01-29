<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'type' => EventLocation::class,
    ];

    /**
     * Get the location for this event (polymorphic).
     */
    public function location(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the users who have RSVP'd to this event.
     */
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get the papers submitted to this event.
     */
    public function papers(): HasMany
    {
        return $this->hasMany(Paper::class);
    }

    /**
     * Get the approved papers (speakers) for this event.
     */
    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'papers')
            ->where('papers.status', 'approved')
            ->withPivot('title', 'description')
            ->withTimestamps();
    }

    /**
     * Get the sponsors for this event.
     */
    public function sponsors(): BelongsToMany
    {
        return $this->belongsToMany(Sponsor::class, 'event_sponsor')
            ->withTimestamps();
    }
}
