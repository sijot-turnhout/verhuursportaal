<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\InvoiceResource\States;

use App\Models\Invoice;
use LogicException;

/**
 * Class InvoiceState
 *
 * Represents the base state class for invoices. This abstract-like class is intended
 * to provide shared functionality for all invoice states. Each state (e.g., Draft, Open,
 * Paid, Void) will extend this class and implement its own specific logic.
 *
 * By default, any attempt to transition an invoice to a different state will throw
 * a `LogicException`, unless explicitly overridden in a child class.
 *
 * @package App\Filament\Clusters\Billing\Resources\InvoiceResource\States
 */
class InvoiceState implements InvoiceStateContract
{
    /**
     * Create a new instance of the InvoiceState.
     *
     * This constructor initializes the state with the associated invoice instance,
     * allowing any subsequent state transitions to act upon the invoice data.
     *
     * @param  Invoice $invoice  The invoice instance associated with this state.
     * @return void
     */
    public function __construct(
        public readonly Invoice $invoice,
    ) {}

    /**
     * Transition the invoice to the "open" state.
     *
     * This method is meant to be overridden by concrete state classes where a valid
     * transition to the "open" state is permitted. In the base class, this method
     * will always throw a `LogicException` indicating that this transition is invalid.
     *
     * @return bool  This will always throw a `LogicException` in the base class.
     *
     * @throws LogicException Always thrown to indicate that transitioning to the "open" state is not allowed from the current state.
     */
    public function transitionToOpen(): bool
    {
        throw new LogicException('Cannot transition to an open invoice on the current state');
    }

    /**
     * Transition the invoice to the "paid" state.
     *
     * This method is intended to be overridden by concrete state classes where a valid
     * transition to the "paid" state is permitted. In the base class, this method
     * will always throw a `LogicException` indicating that this transition is invalid.
     *
     * @return bool This will always throw a `LogicException` in the base class.
     *
     * @throws LogicException Always thrown to indicate that transitioning to a paid invoice is not allowed from the current state.
     */
    public function transitionToPaid(): bool
    {
        throw new LogicException('Cannot transition to an paid invoice on the current state');
    }

    /**
     * Transition the invoice to the "void" state.
     *
     * This method is intended to be overridden by concrete state classes where a valid
     * transition to the "void" state is permitted. In the base class this method
     * will always thow a `LogicException` indicating that this transition is invalid.
     *
     * @return bool This will always throw a `LogicException` in the base class.
     *
     * @throws LogicException Always thrown to indicate that transitioning to a void invoice is not allowed from the current state.
     */
    public function transitionToVoid(): bool
    {
        throw new LogicException('Cannot transition to an void invoice on the current state');
    }

    /**
     * Transition the invoice to the "uncollected" state.
     *
     * This method is intended to be overridden by concrete state classes where a valid
     * transition to the "uncollected" state is permitted. In the base ckass this method
     * will always throw a `LogicException` indicating that this transition is invalid.
     *
     * @return bool This will always throw a `LogicException` in the base class?
     *
     * @throws LogicException Always thrown to indicate that transitioning to a uncollected invoice is not allowed from the current state.
     */
    public function transitionToUnCollected(): bool
    {
        throw new LogicException('Cannot transition to an uncollected sinvoice on the current state');
    }
}
