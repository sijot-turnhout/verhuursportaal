<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource\States\Contracts\LeaseStateAllowanceChecker;
use App\Filament\Resources\LeaseResource\States\Contracts\LeaseStateContract;
use App\Filament\Support\Concerns\InteractsWithNotifications;
use App\Filament\Support\Concerns\UsesAuthenticatedUser;
use App\Models\Lease;
use Exception;

/**
 * Class DefaultLeaseState
 *
 * This class represents the default state of a lease in the system.
 * It implements the LeaseStateAllowanceChecker and LeaseStateContract interfaces,
 * which enforce methods related to checking allowances and defining state-related behaviors.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
class LeaseState implements LeaseStateAllowanceChecker, LeaseStateContract
{
    use InteractsWithNotifications;
    use UsesAuthenticatedUser;

    public function __construct(
        public readonly Lease $lease,
    ) {}

    /**{@inheritDoc} */
    public function transitionToQuotationOption(): void
    {
        throw new Exception('Cannot transition the lease to the quotation option state');
    }

    /** {@inheritDoc} */
    public function transitionToOption(): void
    {
        throw new Exception('Cannot transition to the Option state with the current state');
    }

    /** {@inheritDoc} */
    public function transitionToCancelled(): void
    {
        throw new Exception('Cannot transition to the Cancelled state with the current state');
    }

    /** {@inheritDoc} */
    public function transitionToFinalized(): void
    {
        throw new Exception('Cannot transition to the Finalized state on the current state.');
    }

    /** {@inheritDoc} */
    public function transitionToConfirmed(): void
    {
        throw new Exception('Cannot transition to the Confirmed state with the current state.');
    }

    /** {@inheritDoc} */
    public function allowTransitionToQuotationOption(): bool
    {
        return true;
    }

    /** {@inheritDoc} */
    public function allowTransitionToOption(): bool
    {
        return $this->filamentUser()->can('update', $this->lease)
            && $this->lease->status->isNot(LeaseStatus::Option)
            && $this->lease->status->in([LeaseStatus::Quotation, LeaseStatus::Confirmed, LeaseStatus::Cancelled]);
    }

    /** {@inheritDoc} */
    public function allowTransitionToCancelled(): bool
    {
        return true;
    }

    /** {@inheritDoc} */
    public function allowTransitionToFinalized(): bool
    {
        return $this->filamentUser()->can('update', $this->lease)
            && $this->lease->status->is(LeaseStatus::Confirmed);
    }

    /** {@inheritDoc} */
    public function allowTransitionToConfirmed(): bool
    {
        return $this->filamentUser()->can('update', $this->lease)
            && $this->lease->status->is(LeaseStatus::Quotation)
            || $this->lease->status->is(LeaseStatus::Request)
            || $this->lease->status->is(LeaseStatus::Option);
    }
}
