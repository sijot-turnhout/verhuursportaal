<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;

/**
 * Class LeaseQuotationRequestState
 *
 * This class represents the "Quotation Request" state in the lease lifecycle. It extends the base `LeaseState` class,
 * and is typically used when a lease is in the quotation request phase, meaning a rental offer or quote is being prepared.
 *
 * Override specific methods from the parent `LeaseState` class to define the behavior and state transitions
 * specific to this phase of the lease process.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
final class LeaseQuotationRequestState extends LeaseState
{
    /**
     * {@inheritdoc}
     */
    public function transitionToConfirmed(): bool
    {
        return $this->lease->markAs(LeaseStatus::Confirmed);
    }

    /**
     * {@inheritdoc}
     */
    public function transitionToOption(): bool
    {
        return $this->lease->markAs(LeaseStatus::Option);
    }

    /**
     * {@inheritdoc}
     */
    public function transitionToCancelled(): bool
    {
        return $this->lease->markAs(LeaseStatus::Cancelled);
    }
}
