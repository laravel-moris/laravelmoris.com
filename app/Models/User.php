<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Permissions;
use App\Enums\Roles;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasMedia, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, InteractsWithMedia, Notifiable;

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
            'provider' => 'string',
            'provider_id' => 'string',
        ];
    }

    /**
     * Get the events the user has RSVP'd to.
     */
    public function rsvps(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->as('rsvp')
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
            ->as('paper')
            ->withPivot('title', 'description')
            ->withTimestamps();
    }

    /**
     * Get the user's avatar.
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value): string {
                $mediaUrl = $this->getFirstMediaUrl('avatar', 'webp');

                if (filled($mediaUrl)) {
                    return $mediaUrl;
                }

                return sprintf('https://avatars.laravel.cloud/%s', urlencode($this->name));
            },
        );
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);
    }

    /**
     * Register the media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(80)
            ->nonQueued();
    }

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasPermissionTo(Permissions::AccessAdminPanel->value)
            || $this->hasRole(Roles::SuperAdmin->value);
    }
}
