<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\LeaseStatus;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\Deprecated;

/**
 * Class LeaseBuilder
 *
 * This class extends Laravel's `Builder` and provides custom query-building methods
 * specific to the `Lease` model. It allows setting the status of a lease and unlocking
 * metrics for leases, adding flexibility and convenience for interacting with `Lease` records.
 *
 * @template TModelClass of \App\Models\Lease
 * @extends Builder<\App\Models\Lease>
 *
 * @package App\Builders
 */
final class LeaseBuilder extends Builder
{
    public function depositRepaymentIsDue(): bool
    {
        return (null === $this->model->deposit->refunded_at && optional($this->model->deposit)->refund_at->isPast())
            && $this->model->deposit->status->is(DepositStatus::Paid);
    }

    /**
     * Registers a cancellation for the lease by updating the cancellation reason and timestamp.
     *
     * This method is responsible for handling the cancellation process of a lease. It updates the
     * lease record with the provided cancellation reason and sets the cancellation timestamp to
     * the current date and time. This ensures that the lease is marked as cancelled and the reason
     * for the cancellation is recorded for future reference.
     *
     * @param  string $reason  The reason for cancelling the lease. This should be a descriptive
     *                         explanation provided by the user or system indicating why the lease
     *                         is being cancelled.
     *
     * @return bool            Returns true if the update was successful, indicating that the lease record
     *                         was correctly updated with the cancellation reason and timestamp. Returns
     *                         false if the update failed, which could be due to database errors or invalid
     *                         input.
     */
    public function registerCancellation(string $reason): bool
    {
        return $this->model->update(['cancellation_reason' => $reason, 'cancelled_at' => now()]);
    }

    /**
     * Updates the status of the lease to the specified `LeaseStatus`.
     *
     * This method is used to mark a lease with a new status, such as "Confirmed" or "Finalized".
     *
     * @param  LeaseStatus $leaseStatus  The new status to be applied to the lease.
     * @return bool                      Returns true if the status update is successful, false otherwise.
     */
    #[Deprecated(reason: 'In favor of the new setStatus method on models. (See GH #108)', since: '1.0')]
    public function markAs(LeaseStatus $leaseStatus): bool
    {
        return $this->model->update(['status' => $leaseStatus]);
    }

    /**
     * Unlocks the lease metrics by setting the `metrics_registered_at` column to `null`.
     *
     * This method allows the metrics of a lease to be re-registered or recalculated by clearing the
     * timestamp that locks metric registration.
     *
     * @return bool Returns true if the update is successful, false otherwise.
     */
    public function unlockMetrics(): bool
    {
        return $this->model->update(['metrics_registered_at' => null]);
    }
}
