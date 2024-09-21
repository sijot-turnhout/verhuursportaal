<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\LeaseStatus;
use App\Enums\UserGroup;
use App\Models\Lease;
use App\Models\User;
use App\Support\Features;

/**
 * Class LeasePolicy
 *
 * This policy class defines the authorization logic for actions related to the Lease model.
 * It contains methods to determine whether a user can perform specific actions on leases,
 * based on their user group and the current state of the lease.
 *
 * @package App\Policies
 */
final readonly class LeasePolicy
{
    /**
     * Determine if the user can finalize metrics for the lease.
     *
     * This method checks if the the utility metrics feature is enabled and whether the lease is confirmed or finalized.
     *
     * @param  User  $user   The user attempting to finalize metrics.
     * @param  Lease $lease  The lease instance for which metrics are being finalized.
     * @return bool          True is the user can finalize metrics, false otherwise.
     */
    public function finalizeMetrics(User $user, Lease $lease): bool
    {
        return Features::enabled(Features::utilityMetrics())
            && $lease->status->in(enums: [LeaseStatus::Confirmed, LeaseStatus::Finalized]);
    }

    /**
     * Determine if the user can finalize metrics for the lease.
     *
     * This method checks if the utility metrics feature is enabled and where the lease is confirmed or finalized.
     *
     * @param  User  $user   The user attempting to finalize metrics.
     * @param  Lease $lease  The lease instance for which metrics are beind finalized
     * @return bool          True if the user can finalize metrics, false otherwise.
     */
    public function generateInvoice(User $user, Lease $lease): bool
    {
        return $lease->status->in(enums: [LeaseStatus::Confirmed, LeaseStatus::Finalized])
            && $lease->invoice()->doesntExist()
            && $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster]);
    }


    /**
     * Determine if the user can view the invoice for the lease.
     *
     * This method checks if the lease is confirmed or finalized, that an invoice exists,
     * and that the user belgons to the Webmaster or RVB user groups.
     *
     * @param  User  $user   The user attempting to view the invoice.
     * @param  Lease $lease  The lease instance for which the invoice is being viewed.
     * @return bool          True if the user can view the invoice, false otherwise.
     */
    public function viewInvoice(User $user, Lease $lease): bool
    {
        return $lease->status->in(enums: [LeaseStatus::Confirmed, LeaseStatus::Finalized])
            && $lease->invoice()->exists()
            && $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster]);
    }

    /**
     * Determine if the user can update the lease.
     *
     * This method allows updates only for users in the Rvb or Webmaster user groups.
     *
     * @param  User  $user   The user attempting to update the lease.
     * @param  Lease $lease  The lease instance being updated.
     * @return bool          True if the user can update the lease, false otherwise.
     */
    public function update(User $user, Lease $lease): bool
    {
        return $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster])
            && $lease->status->notIn(enums: [LeaseStatus::Cancelled, LeaseStatus::Finalized]);
    }

    /**
     * Determine if the user can unlock the metrics for the given lease.
     *
     * The metrics can be unlocked if the feature is enabled, the lease has registered metrics
     * and the use belongs to the 'Rvb' or 'webmaster' user group.
     *
     * @param  User  $user   The user attempting to unlock the metrics.
     * @param  Lease $lease  The lease for which the metrics are being unlocked.
     * @return bool          True if the user can unlock the metrics, false otherwise.
     */
    public function unlockMetrics(User $user, Lease $lease): bool
    {
        return Features::enabled(Features::utilityMetrics())
            && $lease->hasRegisteredMetrics()
            && $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster]);
    }
}
