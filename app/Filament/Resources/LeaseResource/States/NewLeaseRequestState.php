<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;

/**
 * NewLeaseRequestState Class
 *
 * Represents the initial state of a lease when a new request has been made. This class extends the
 * base LeaseState class and provides the specific implementation for a lease that is in the request phase.
 *
 * In this state, the lease is newly requested and awaiting further action, such as review, approval, or
 * additional information. This is typically the starting point in the lease lifecycle.
 *
 * @see tests/Feature/LeaseResource/States/NewLeaseRequestStateTest.php
 */
final class NewLeaseRequestState extends LeaseState
{
    /**
     * {@inheritDoc}
     * @todo Implement method to generate an empty quotation in the backend system of the application.
     */
    public function transitionToQuotationOption(): void
    {
        $this->lease->update(['status' => LeaseStatus::Quotation]);
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToOption(): void
    {
        $this->lease->update(['status' => LeaseStatus::Option]);
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToCancelled(): void
    {
        $this->lease->update(['status' => LeaseStatus::Cancelled]);
    }
}
