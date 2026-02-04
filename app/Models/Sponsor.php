<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Sponsor extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * Get the events sponsored by this sponsor.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_sponsor')
            ->withTimestamps();
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);
    }

    /**
     * Get the user's avatar.
     */
    protected function logo(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value): string {
                $mediaUrl = $this->getFirstMediaUrl('logo', 'webp');

                if (filled($mediaUrl)) {
                    return $mediaUrl;
                }

                return sprintf('https://avatars.laravel.cloud/%s', urlencode($this->name));
            },
        );
    }

    /**
     * Register the media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(80)
            ->width(400)
            ->nonQueued();
    }
}
