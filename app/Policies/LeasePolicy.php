<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\LeaseStatus;
use App\Enums\UserGroup;
use App\Features\UtilityMetrics;
use App\Models\Lease;
use App\Models\User;
use Laravel\Pennant\Feature;

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
        return $lease->status->in(enums: [LeaseStatus::Confirmed, LeaseStatus::Finalized]);
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
     * And when the lease is not Cancelled, Finalized or archived.
     *
     * @param  User  $user   The user attempting to update the lease.
     * @param  Lease $lease  The lease instance being updated.
     * @return bool          True if the user can update the lease, false otherwise.
     */
    public function update(User $user, Lease $lease): bool
    {
        return $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster])
            && $lease->status->notIn(enums: [LeaseStatus::Cancelled, LeaseStatus::Finalized, LeaseStatus::Archived]);
    }

    /**
     * Determine whether the user can delete a specific lease.
     *
     * This method checks if the user has permission to delete a lease based on their user group and the lease's status.
     *
     * - Webmasters are always allowed to delete leases.
     * - Users in the 'Vzw' or 'Rvb' groups can delete leases only if the lease status is either 'Cancelled' or 'Finalized'.
     *
     * @param  User   $user  The authenticated user attempting to delete the lease.
     * @param  Lease  $lease The lease instance the user is attempting to delete.
     * @return bool          Returns true if the user has permission to delete the lease, false otherwise.
     */
    public function delete(User $user, Lease $lease): bool
    {
        // Webmasters have unconditional permission to delete leases
        if ($user->user_group->is(UserGroup::Webmaster)) {
            return true;
        }

        // Users in 'Vzw' or 'Rvb' groups can delete leases with 'Cancelled' or 'Finalized' status
        return (bool) (
            $lease->status->in([LeaseStatus::Cancelled, LeaseStatus::Finalized]) &&
            $user->user_group->in([UserGroup::Vzw, UserGroup::Rvb])
        );
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
        return Feature::active(UtilityMetrics::class)
            && $lease->hasRegisteredMetrics()
            && $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster]);
    }

    /**
     * Determines if a quotation can be generated for a lease by a specific user.
     *
     * This method checks if the lease meets the conditions for generating a quotation.
     * It verifies that the lease is in a specific status, that no quotation exists
     * already, and that the user belongs to an authorized user group.
     *
     * @param  User  $user   The user requesting the quotation.
     * @param  Lease $lease  The lease for which a quotation may be generated.
     * @return bool          True if the user can generate a quotation, otherwise false.
     */
    public function generateQuotation(User $user, Lease $lease): bool
    {
        return $lease->status->in(enums: [LeaseStatus::Option, LeaseStatus::Request, LeaseStatus::Quotation])
            && $lease->quotation()->doesntExist()
            && $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster]);
    }

    /**
     * Determines if a lease can be archived by a specific user.
     *
     * This method checks if a lease is eligible for archiving, ensuring the lease
     * is in a cancellable or finalized status and has not been archived already.
     * It also verifies that the user belongs to an authorized user group to perform
     * the archive action.
     *
     * @param  User $user    The user requesting the archive action.
     * @param  Lease $lease  The lease that may be archived.
     * @return bool          True if the user can archive the lease, otherwise false.
     */
    public function archive(User $user, Lease $lease): bool
    {
        return $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Vzw, UserGroup::Webmaster])
            && $lease->status->in([LeaseStatus::Cancelled, LeaseStatus::Finalized])
            && $lease->status->isNot(LeaseStatus::Archived);
    }

    public function configureDeposit(User $user, Lease $lease): bool
    {
        return $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster])
            && $lease->deposit()->doesntExist();
    }
}
