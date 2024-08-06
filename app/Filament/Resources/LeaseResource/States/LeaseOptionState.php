<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;

/**
 * LeaseOptionState Class
 *
 * Represents the state of a lease that is currently in the "Option" phase. This class extends the base
 * LeaseState class and provides the specific implementation for a lease that is being considered but
 * not yet confirmed or finalized.
 *
 * In this state, the lease is typically reserved or held under provisional terms. The Option state
 * indicates that there is an intention to proceed with the lease, subject to further decisions or
 * confirmation.
 *
 * @see Tests/Feature/LeaseResource/States/LeaseOptionStateTest.php
 */
final class LeaseOptionState extends LeaseState
{
    /** {@inheritDoc} */
    public function transitionToQuotationOption(): void
    {
        $this->lease->setStatus(LeaseStatus::Quotation);
    }

    /** {@inheritDOc} */
    public function transitionToConfirmed(): void
    {
        $this->lease->setStatus(LeaseStatus::Confirmed);
    }

    /** {@inheritDoc} */
    public function transitionToCancelled(): void
    {
        $this->lease->setStatus(LeaseStatus::Cancelled);
    }
}
