<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaperStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paper extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'status' => PaperStatus::class,
        'description' => 'string',
    ];

    /**
     * Get the user (speaker) who submitted this paper.
     */
    public function speaker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the event this paper was submitted to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
