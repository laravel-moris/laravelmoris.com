<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventLocation;
use App\Enums\RsvpStatus;
use App\Observers\EventObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[ObservedBy(EventObserver::class)]
class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'type' => EventLocation::class,
            'capacity' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $event->slug = static::generateUniqueSlug($event->title);
            }
        });

        static::updating(function (Event $event) {
            if ($event->isDirty('title') && ! $event->isDirty('slug')) {
                $event->slug = static::generateUniqueSlug($event->title);
            }
        });
    }

    private static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $baseSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        return $slug;
    }

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
            ->as('rsvp')
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

    public function isFull(): bool
    {
        if ($this->capacity === null) {
            return false;
        }

        return $this->attendees()
            ->where('event_user.status', RsvpStatus::Going->value)
            ->count() >= $this->capacity;
    }

    public function availableSpots(): ?int
    {
        if ($this->capacity === null) {
            return null;
        }

        $confirmed = $this->attendees()
            ->where('event_user.status', RsvpStatus::Going->value)
            ->count();

        return max(0, $this->capacity - $confirmed);
    }

    /**
     * Get the approved papers (speakers) for this event.
     */
    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'papers')
            ->where('papers.status', 'approved')
            ->as('paper')
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
