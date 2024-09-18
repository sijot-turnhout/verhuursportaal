<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;

/**
 * Class LeaseRequestState
 *
 * This class represents the "Lease Request" state in the lease lifecycle. It extends the base `LeaseState` class,
 * and is typically used when the lease is in the initial "request" phase, where a new lease application has been submitted.
 *
 * Specific behaviors and state transitions related to the lease request can be implemented by overriding methods from
 * the parent class, `LeaseState`.
 *
 * @package App\Filament\Resoources\LeaseResource\States
 */
final class LeaseRequestState extends LeaseState
{
    /**
     * {@inheritDoc}
     */
    public function transitionToQuotationRequest(): void
    {
        $this->lease->markAs(LeaseStatus::Quotation);
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToOption(): void
    {
        $this->lease->markAs(LeaseStatus::Option);
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToConfirmed(): void
    {
        $this->lease->markAs(LeaseStatus::Confirmed);
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToCancelled(): void
    {
        $this->lease->markAs(LeaseStatus::Cancelled);
    }
}
