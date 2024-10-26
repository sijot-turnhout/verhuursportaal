<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\States;

use LogicException;

/**
 * Interface QuotationStateContract
 *
 * This contract defines the methods required for handling state transitions of a Quotation.
 * Each concrete state class (e.g., Draft, Open, Accepted) implements this interface to provide
 * the specific logic for transitioning a quotation between its different lifecycle states.
 *
 * The state pattern is employed here, where each state manages its own permissible transitions
 * by implementing these methods. Depending on the current state of a quotation, certain
 * transitions may not be allowed, and those methods should handle such restrictions.
 *
 * Methods in this interface are used to define the behavior when moving a quotation from
 * one state to another, such as transitioning from Open to Accepted, or from Open to Declined.
 *
 * @package App\Filament\Clusters\Billing\Resources\QuotationResource\States
 */
interface QuotationStateContract
{
    /**
     * Transition the quotation to the Open state.
     *
     * This method is expected to handle all the necessary changes
     * to move the quotation into the "Open" state.
     *
     * @throws LogicException If the transition is not allowed from the current state.
     */
    public function transitionToOpen(): void;

    /**
     * Transition the quotation to the Declined state.
     *
     * This method is expected to handle all the necessary changes
     * to move the quotation into the "Declined" state.
     *
     * @throws LogicException If the transition is not allowed from the current state.
     */
    public function transitionToDeclined(): void;

    /**
     * Transition the quotation to the Accepted state.
     *
     * This method is expected to handle all the necessary changes
     * to move the quotation into the "Accepted" state.
     *
     * @throws LogicException If the transition is not allowed from the current state.
     */
    public function transitionToAccepted(): void;

    /**
     * Transition the quotation to the Expired state.
     *
     * This method is expected to handle all the necessary changes
     * to move the quotation into the "Expired" state.
     *
     * @throws LogicException If the transition is not allowed from the current state.
     */
    public function transitionToExpired(): void;
}
