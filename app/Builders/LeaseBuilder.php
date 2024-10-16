<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\LeaseStatus;
use Illuminate\Database\Eloquent\Builder;

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
    /**
     * Updates the status of the lease to the specified `LeaseStatus`.
     *
     * This method is used to mark a lease with a new status, such as "Confirmed" or "Finalized".
     *
     * @param  LeaseStatus $leaseStatus  The new status to be applied to the lease.
     * @return bool                      Returns true if the status update is successful, false otherwise.
     */
    public function markAs(LeaseStatus $leaseStatus): bool
    {
        return $this->model->update(['status' => $leaseStatus]);
    }

    public function purgeRelatedLeaseInformation(): void
    {
        dd($this);
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
