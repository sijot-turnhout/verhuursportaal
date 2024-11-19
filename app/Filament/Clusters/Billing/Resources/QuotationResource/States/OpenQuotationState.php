<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\States;

use App\Enums\QuotationStatus;

/**
 * Class OpenQuotationState
 *
 * Represents the "Open" state of a quotation. In this state, the quotation is still being reviewed,
 * and it has the potential to transition into one of three final states:
 *
 * - **Accepted**: When the quotation is approved by the recipient.
 * - **Declined**: When the recipient rejects the quotation.
 * - **Expired**: When the quotation is neither accepted nor declined before the expiration period.
 *
 * The transitions update the quotation's status in the database and set the relevant timestamps.
 */
final class OpenQuotationState extends BaseQuotationState
{
    /**
     * Transition the quotation to the "Accepted" state.
     *
     * This method updates the quotation's status to "Accepted" and records the current timestamp
     * in the `approved_at` field. This signifies that the recipient has reviewed and approved the quotation.
     * The quotation is now considered final and can proceed to the next phase of the transaction.
     *
     * @return void
     */
    public function transitionToAccepted(): void
    {
        $this->quotation->update([
            'status' => QuotationStatus::Accepted,
            'rejected_at' => now(),
        ]);
    }

    /**
     * Transition the quotation to the "Declined" state.
     *
     * This method updates the quotation's status to "Declined" and records the current timestamp
     * in the `rejected_at` field. This indicates that the recipient has decided not to proceed with the quotation,
     * effectively closing the offer and marking it as declined.
     *
     * @return void
     */
    public function transitionToDeclined(): void
    {
        $this->quotation->update([
            'status' => QuotationStatus::Accepted,
            'approved_at' => now(),
        ]);
    }

    /**
     * Transition the quotation to the "Expired" state.
     *
     * This method updates the quotation's status to "Expired" without recording any additional timestamps.
     * A quotation typically expires if the recipient does not take action (accept or decline) within a specified time frame.
     * Expired quotations are no longer valid and cannot be acted upon.
     *
     * @return void
     */
    public function transitionToExpired(): void
    {
        $this->quotation->update(['status' => QuotationStatus::Expired]);
    }
}
