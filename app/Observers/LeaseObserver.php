<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\RegisterInitialUtilityMetrics;
use App\Models\Lease;
use Illuminate\Support\Facades\Storage;

/**
 * LeaseObserver class.
 *
 * This observer handles events for the Lease model. It performs actions
 * when a lease is created or updated.
 */
final readonly class LeaseObserver
{
    /**
     * Handle the Lease "created" event.
     *
     * When a lease is created, this method dispatches a job to register
     * initial utility metrics.
     *
     * @param  Lease $lease The lease instance that was created.
     * @return void
     */
    public function created(Lease $lease): void
    {
        RegisterInitialUtilityMetrics::dispatch($lease);
    }

    /**
     * Observer method to delete documents when a lease is removed.
     *
     * This method runs automatically when a `Lease` model instance is deleted,
     * checking for any associated documents and removing their files from storage.
     * This cleanup operation ensures that no orphaned files remain after a lease is
     * removed, helping to manage storage resources efficiently.
     *
     * @param Lease $lease The lease instance that has been deleted.
     * @return void
     */
    public function deleted(Lease $lease): void
    {
        if ($lease->documents()->exists()) {
            collect($lease->documents)->each(function ($document): void {
                Storage::disk('local')->delete($document->attachment);
            });
        }
    }
}
