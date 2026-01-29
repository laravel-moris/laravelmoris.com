<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the events the user has RSVP'd to.
     */
    public function rsvps(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get the papers submitted by the user.
     */
    public function papers(): HasMany
    {
        return $this->hasMany(Paper::class);
    }

    /**
     * Get the events where the user is a speaker (has approved papers).
     */
    public function speakingEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'papers')
            ->where('papers.status', 'approved')
            ->withPivot('title', 'description')
            ->withTimestamps();
    }

    /**
     * Get the user's avatar.
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): ?string {
                return filled($value) ? Storage::url($value) : sprintf('https://avatars.laravel.cloud/%s', urlencode($this->name));
            },
        );
    }
}
