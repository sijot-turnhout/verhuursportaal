<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;
use App\Filament\Support\StateMachines\StateTransitionGuard;
use App\Filament\Support\StateMachines\StateTransitionGuardContract;
use App\Models\Lease;
use App\Support\Auditable;
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
class LeaseState implements LeaseStateContract, StateTransitionGuardContract
{
    use Auditable;
    use StateTransitionGuard;

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
    public function transitionToQuotationRequest(): void
    {
        throw new LogicException('The transition to quotation request is not valid on the current state.');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToOption(): void
    {
        throw new LogicException('The transition to optional reservation is not valid on the current state');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToConfirmed(): void
    {
        throw new LogicException('The transition to finalized state is not valid on the current state.');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToCompleted(): void
    {
        throw new LogicException('The transition to confirmed is not valid on the current state.');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToCancelled(): void
    {
        throw new LogicException('The transition to the cancelled state is not valid on the current state');
    }

    /**
     * Registers an audit log entry for a lease status change.
     *
     * This method records a status change in the activity log for the lease. It logs an event
     * labeled 'statuswijziging' (status change) and provides a detailed description indicating
     * the old and new statuses. The description is localized using the `trans()` function.
     *
     * @param  LeaseStatus $status  The new lease status that the lease has been changed to.
     * @return void
     */
    protected function registerStatusChangeInAuditLog(LeaseStatus $status): void
    {
        $this->registerAuditEntry(
            logName: 'verhuring',
            event: 'statuswijziging',
            performedOn: $this->lease,
            auditEntry: trans("Heeft de status van de verhuring gewijzigd van :old naar :new", [
                'old' => $this->lease->status->getLabel(), 'new' => $status->getLabel(),
            ])
        );
    }
}
