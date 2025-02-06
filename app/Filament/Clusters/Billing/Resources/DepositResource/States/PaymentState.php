<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\States;

use App\Concerns\InteractsWithAuditingSystem;
use App\Constracts\Eloquent\Auditable;
use App\Models\Deposit;
use LogicException;

/**
 * Class PaymentState
 *
 * This class implements the PaymentStateContract providing the logic for trnasitioning a deposit between different states.
 * Each transition method throws a LogicException by default, signaling that the transition is not valid in the surrent state.
 * Specific Deposit states should extend this class and override this method to provide the correct transition behaviour.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\States
 */
class PaymentState implements PaymentStateContract
{
    /**
     * PaymentState constructor
     *
     * @param  Deposit $deposit The deposiut model that this StateMachine is associated with
     * @return void
     */
    public function __construct(
        public readonly Deposit $deposit
    ) {}

    /** {@inheritDoc} */
    public function transitionToDueRefund(): void
    {
        throw new LogicException(message: 'Canoot transition to the due state on the current state.');
    }

    /** {@inheritDoc} */
    public function transitionToFullyRefunded(): void
    {
        throw new LogicException(message: 'Cannot transition to the fully refunded state on the current state.');
    }

    /** {@inheritDoc} */
    public function transitionToPaid(): void
    {
        throw new LogicException(message: 'Cannot transition to the Paid state on the current state.');
    }

    /** {@inheritDoc} */
    public function transitionToPartiallyRefunded(): void
    {
        throw new LogicException(message: 'Cannot transition to the Partially refunded state on the current state.');
    }

    /** {@inheritDoc} */
    public function transitionToRevoked(): void
    {
        throw new LogicException(message: 'Cannot transition to the Revoked state on the current state');
    }
}
