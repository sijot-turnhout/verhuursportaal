<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States\Contracts;

/**
 * LeaseStateContract Interface
 *
 * This interface defines the contract for transitioning between different states in the lease lifecycle.
 * Implementing classes should provide the logic for handling these state transitions.
 *
 * The methods outlined in this interface represent actions that change the state of a lease, and must be
 * implemented by any class that wishes to represent a specific state within the lease state machine.
 */
interface LeaseStateContract
{
    /**
     * Transition the lease to the Quotation or Option state.
     *
     * This method should contain the logic required to move a lease to the Quotation/Option state,
     * which may involve providing an offer or preliminary agreement.
     */
    public function transitionToQuotationOption(): void;

    /**
     * Transition the lease to the Option state.
     *
     * This method handles the logic to set the lease to the Option state, indicating a provisional
     * reservation or interest without full commitment.
     */
    public function transitionToOption(): void;

    /**
     * Transition the lease to the Cancelled state.
     *
     * This method should handle the steps necessary to cancel the lease, terminating the agreement
     * and potentially triggering any associated cancellation policies or procedures.
     */
    public function transitionToCancelled(): void;

    /**
     * Transition the lease to the Finalized state.
     *
     * This method should implement the logic required to complete and finalize the lease agreement,
     * marking the end of the lease lifecycle.
     */
    public function transitionToFinalized(): void;

    /**
     * Transition the lease to the Confirmed state.
     *
     * This method should define the process for confirming the lease, solidifying the agreement
     * between all parties involved.
     *
     */
    public function transitionToConfirmed(): void;
}
