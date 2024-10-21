<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\QuotationStatus;
use App\Models\Quotation;
use App\Models\User;

/**
 * Class QuotationPolicy
 *
 * This class defines the authorization policies for various actions related to the `Quotation` model.
 * It checks whether a user has the necessary permissions to finalize, update, decline, or approve a quotation, based on their user group
 * and the current status of the quotation.
 *
 * Users who are part of the 'Rvb' (possibly a review board) or 'Webmaster' groups
 * are permitted to take certain actions when the quotation is in specific states.
 *
 * @package App\Policies
 */
final readonly class QuotationPolicy
{
    /**
     * Determine if the user can finalize a draft quotation.
     *
     * A quotation can only be finalized if it is in the "Draft" status and
     * the user belongs to the 'Rvb' or 'Webmaster' group.
     *
     * @param  User      $user       The user attempting the action.
     * @param  Quotation $quotation  The quotation to be finalized.
     * @return bool                  True if the user can finalize the quotation, false otherwise.
     */
    public function finalize(User $user, Quotation $quotation): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && QuotationStatus::Draft === $quotation->status;
    }

    /**
     * Determine if the user can update a draft quotation.
     *
     * A quotation can only be updated if it is in the "Draft" status and
     * the user belongs to the 'Rvb' or 'Webmaster' group.
     *
     * @param  User      $user       The user attempting the action.
     * @param  Quotation $quotation  The quotation to be updated.
     * @return bool                  True if the user can update the quotation, false otherwise.
     */
    public function update(User $user, Quotation $quotation): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && $quotation->status->is(QuotationStatus::Draft);
    }

    /**
     * Determine if the user can decline an open quotation.
     *
     * A quotation can only be declined if it is in the "Open" status and
     * the user belongs to the 'Rvb' or 'Webmaster' group.
     *
     * @param  User      $user       The user attempting the action.
     * @param  Quotation $quotation  The quotation to be declined.
     * @return bool                  True if the user can decline the quotation, false otherwise.
     */
    public function decline(User $user, Quotation $quotation): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && QuotationStatus::Open === $quotation->status;
    }

    /**
     * Determine if the user can approve an open quotation.
     *
     * A quotation can only be approved if it is in the "Open" status and
     * the user belongs to the 'Rvb' or 'Webmaster' group.
     *
     * @param  User      $user       The user attempting the action.
     * @param  Quotation $quotation  The quotation to be approved.
     * @return bool                  True if the user can approve the quotation, false otherwise.
     */
    public function approve(User $user, Quotation $quotation): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && QuotationStatus::Open === $quotation->status;
    }
}
