<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserGroup;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use App\Models\User;

/**
 * Class DepositPolicy
 *
 * This policy class defines permissions for actions on the `Deposit` model.
 * It includes rules to determine if a user can partially or fully refund deposits or mark them as fully withdrawn.
 * Access is restricted to users in specific roles, verified in the `before` method.
 *
 * @package App\Policies
 */
final readonly class DepositPolicy
{
    /**
     * A global authorization check applied before other policy methods.
     * Only users belonging to specific user groups are permitted to perform deposit-related actions.
     *
     * @param  User $user       The user attempting the action.
     * @param  string $ability  The specific action being checked.
     * @return bool|null        Returns false to deny all actions if the user lacks necessary permissions, or null to allow further checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ( ! $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Vzw, UserGroup::Webmaster])) {
            return false;
        }

        return null;
    }

    /**
     * Determines if the user can mark a deposit as partially refunded.
     * This action is allowed only if the deposit status is `Paid`.
     *
     * @param  User     $user     The user attempting the action.
     * @param  Deposit  $deposit  The deposit being modified.
     * @return bool               True if the deposit can be marked as partially refunded, false otherwise.
     */
    public function markAsPartiallyRefunded(User $user, Deposit $deposit): bool
    {
        return $deposit->status->is(DepositStatus::Paid);
    }

    /**
     * Determines if the user can mark a deposit as fully refunded.
     * This action is allowed only if the deposit status is `Paid`.
     *
     * @param  User     $user     The user attempting the action.
     * @param  Deposit  $deposit  The deposit being modified.
     * @return bool               True if the deposit can be marked as fully refunded, false otherwise.
     */
    public function markAsFullyRefunded(User $user, Deposit $deposit): bool
    {
        return $deposit->status->is(DepositStatus::Paid);
    }

    /**
     * Determines if the user can mark a deposit as fully withdrawn.
     * This action is allowed only if the deposit status is `Paid`.
     *
     * @param  User     $user     The user attempting the action.
     * @param  Deposit  $deposit  The deposit being modified.
     * @return bool               True if the deposit can be marked as fully withdrawn, false otherwise.
     */
    public function markAsFullyWithdrawn(User $user, Deposit $deposit): bool
    {
        return $deposit->status->is(DepositStatus::Paid);
    }
}
