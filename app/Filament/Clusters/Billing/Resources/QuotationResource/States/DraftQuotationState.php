<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\States;

use App\Enums\QuotationStatus;

/**
 * Class DraftQuotationState
 *
 * This class represents the "Draft" state of a Quotation.
 * In this state, the quotation is still being prepared and has not yet been sent to the client.
 * The main functionality of this state is transitioning the quotation to the "Open" state, which signifies that
 * the quotation has been finalized and made available to the client.
 *
 * Transitioning to the "Open" state includes setting the status to 'Open' and calculating
 * an expiration date, typically set two weeks from the current date.
 *
 * @package App\Filament\Clusters\Billing\Resources\QuotationResource\States
 */
final class DraftQuotationState extends BaseQuotationState
{
    /**
     * Transition the quotation from the "Draft" state to the "Open" state.
     *
     * This method updates the status of the quotation to 'Open' and sets an expiration date that is two weeks from the current date.
     * The "Open" state indicates that the quotation is now active and viewable by the client.
     *
     * @return void
     */
    public function transitionToOpen(): void
    {
        $this->quotation->update([
            'status' => QuotationStatus::Open,
            'expires_at' => now()->addWeeks(2),
        ]);
    }
}
