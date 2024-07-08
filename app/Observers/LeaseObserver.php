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

    /**
     * Handle the Lease "updated" event.
     *
     * When a lease is updated, this method checks if feedback notification
     * can be sent. If so, it schedules the feedback notification to be sent
     * in 2 months.
     *
     * @param  Lease $lease The lease instance that was updated.
     * @return void
     */
    public function updated(Lease $lease): void
    {
        if ($lease->canSendOutFeedbackNotification()) {
            $lease->sendFeedbackNotification(now()->addMonths(2));
        }
    }
}
