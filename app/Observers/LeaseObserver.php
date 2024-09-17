<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\RegisterInitialUtilityMetrics;
use App\Models\Lease;

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
}
