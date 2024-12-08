<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Models\Lease;
use LogicException;

/**
 * Class LeaseState
 *
 * This class implements both LeaseStateContract and StateTransitionAuthorizationCheckerContract,
 * providing logic for transitioning a lease between different states. Each transition method
 * throws a LogicException by default, signaling that the transition is not valid in the current
 * state. Specific lease states should extend this class and override these methods to provide
 * the correct transition behavior.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
class LeaseState implements LeaseStateContract
{
    /**
     * LeaseState constructor.
     *
     * @param  Lease $lease The lease model that this state is associated with.
     * @return void
     */
    public function __construct(
        public readonly Lease $lease,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function transitionToQuotationRequest(): bool
    {
        throw new LogicException('The transition to quotation request is not valid on the current state.');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToOption(): bool
    {
        throw new LogicException('The transition to optional reservation is not valid on the current state');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToConfirmed(): bool
    {
        throw new LogicException('The transition to finalized state is not valid on the current state.');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToCompleted(): bool
    {
        throw new LogicException('The transition to confirmed is not valid on the current state.');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToCancelled(): bool
    {
        throw new LogicException('The transition to the cancelled state is not valid on the current state');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToArchived(): void
    {
        throw new LogicException('The transition to the archived state is not allowed on the current state');
    }

}
