<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OnlineLocation extends Model
{
    use HasFactory;

    /**
     * Get the events associated with this online location.
     */
    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'location');
    }
}
