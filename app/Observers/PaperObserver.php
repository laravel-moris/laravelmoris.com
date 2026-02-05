<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\PaperStatus;
use App\Models\Paper;

final class PaperObserver
{
    /**
     * Handle the Paper "created" event.
     */
    public function created(Paper $paper): void
    {
        if ($paper->status === PaperStatus::Approved) {
            $paper->speaker->increment('papers_count_cache');
            $paper->speaker->update(['counts_cached_at' => now()]);
        }
    }

    /**
     * Handle the Paper "updated" event.
     */
    public function updated(Paper $paper): void
    {
        // Check if status changed
        if (! $paper->wasChanged('status')) {
            return;
        }

        $originalStatus = $paper->getOriginal('status');
        $newStatus = $paper->status;

        // Convert to enum for comparison if needed
        $originalStatusEnum = is_string($originalStatus) ? PaperStatus::from($originalStatus) : $originalStatus;

        // Status changed TO approved
        if ($newStatus === PaperStatus::Approved && $originalStatusEnum !== PaperStatus::Approved) {
            $paper->speaker->increment('papers_count_cache');
            $paper->speaker->update(['counts_cached_at' => now()]);
        }

        // Status changed FROM approved
        if ($newStatus !== PaperStatus::Approved && $originalStatusEnum === PaperStatus::Approved) {
            $paper->speaker->decrement('papers_count_cache');
            $paper->speaker->update(['counts_cached_at' => now()]);
        }
    }

    /**
     * Handle the Paper "deleted" event.
     */
    public function deleted(Paper $paper): void
    {
        if ($paper->status === PaperStatus::Approved) {
            $paper->speaker->decrement('papers_count_cache');
            $paper->speaker->update(['counts_cached_at' => now()]);
        }
    }

    /**
     * Handle the Paper "restored" event.
     */
    public function restored(Paper $paper): void
    {
        if ($paper->status === PaperStatus::Approved) {
            $paper->speaker->increment('papers_count_cache');
            $paper->speaker->update(['counts_cached_at' => now()]);
        }
    }
}
