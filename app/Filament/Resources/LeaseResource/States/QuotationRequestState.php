<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;

/**
 * LeaseQuotationRequestState Class
 *
 * Represents the state of a lease when a quotation has been requested. This class extends the base
 * LeaseState class and provides the specific implementation for a lease that is in the quotation request phase.
 *
 * In this state, a potential lessee has requested a quotation for the lease terms, including pricing and
 * conditions. It is a preliminary stage where the details of the lease offer are being prepared and communicated.
 *
 * @see tests/Feature/LeaseResource/States/LeaseQuotationRequestStateTest.php
 */
final class QuotationRequestState extends LeaseState
{
    /** {@inheritDoc} */
    public function transitionToConfirmed(): void
    {
        $this->lease->setStatus(LeaseStatus::Confirmed);
    }

    /** {@inhertitDoc} */
    public function transitionToCancelled(): void {}

    /** {@inhertitDoc} */
    public function transitionToOption(): void {}
}
