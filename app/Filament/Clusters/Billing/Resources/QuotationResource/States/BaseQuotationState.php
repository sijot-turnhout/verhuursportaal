<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\States;

use App\Models\Quotation;
use LogicException;

/**
 * Class BaseQuotationState
 *
 * This class serves as the base state for all quotation states in the system.
 * It implements the QuotationStateContract and provides default behaviors for state transitions.
 * By default, all transitions throw a LogicException, which should be overridden by specific state classes that allow valid transitions.
 * This design enforces the state pattern, where each quotation state can define its own valid transitions to other states.
 *
 * @package App\Filament\Clusters\Billing\Resources\QuotationResource\States
 */
class BaseQuotationState implements QuotationStateContract
{
    /**
     * The Quotation instance tied to this state.
     *
     * @param  Quotation $quotation The quotation for which this state applies.
     * @return void
     */
    public function __construct(
        public readonly Quotation $quotation,
    ) {}

    /**
     * Transition the quotation to the Open state.
     *
     * This method should be overridden in the specific state class where
     * this transition is allowed. Otherwise, it throws a LogicException
     * to indicate that the transition is invalid from the current state.
     *
     * @throws LogicException If the transition is not allowed from the current state.
     */
    public function transitionToOpen(): void
    {
        throw new LogicException('Cannot transition to the open state on the current state');
    }

    /**
     * Transition the quotation to the Declined state.
     *
     * This method should be overridden in the specific state class where
     * this transition is allowed. Otherwise, it throws a LogicException
     * to indicate that the transition is invalid from the current state.
     *
     * @throws LogicException If the transition is not allowed from the current state.
     */
    public function transitionToDeclined(): void
    {
        throw new LogicException('Cannot transition to the declined state on the current state');
    }

    /**
     * Transition the quotation to the Accepted state.
     *
     * This method should be overridden in the specific state class where
     * this transition is allowed. Otherwise, it throws a LogicException
     * to indicate that the transition is invalid from the current state.
     *
     * @throws LogicException If the transition is not allowed from the current state.
     */
    public function transitionToAccepted(): void
    {
        throw new LogicException('Cannot transition to the accepted state on the current state');
    }

    /**
     * Transition the quotation to the Expired state.
     *
     * This method should be overridden in the specific state class where
     * this transition is allowed. Otherwise, it throws a LogicException
     * to indicate that the transition is invalid from the current state.
     *
     * @throws LogicException If the transition is not allowed from the current state.
     */
    public function transitionToExpired(): void
    {
        throw new LogicException('Cannot transition to the expired state on the current state');
    }
}
