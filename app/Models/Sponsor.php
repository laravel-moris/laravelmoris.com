<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sponsor extends Model
{
    use HasFactory;

    /**
     * Get the events sponsored by this sponsor.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_sponsor')
            ->withTimestamps();
    }
}
