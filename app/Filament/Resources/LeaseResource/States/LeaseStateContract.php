<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Filament\Resources\LeaseResource\ValueObjects\CancellationDataObject;

/**
 * Interface LeaseStateContract
 *
 * This interface defines the contract for transitioning a lease between various states.
 * Implementations of this interface should handle the logic for transitioning a lease to different
 * states, representing the lifecycle of a rental application. Each method represents a specific
 * transition, providing control over the lease's progression.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
interface LeaseStateContract
{
    /**
     * Transitions the lease to the "Quotation Request" (Nieuwe aanvraag) state.
     *
     * This method initiates the transition to the quotation request state, which represents
     * the initial phase where the rental request is submitted and a quote is being prepared.
     *
     * @return bool
     */
    public function transitionToQuotationRequest(): bool;

    /**
     * Transitions the lease to the "Option" (Optie) state.
     *
     * This method handles the transition to the optional state, where the lease is provisionally
     * reserved or held as an option pending further confirmation.
     *
     * @return bool
     */
    public function transitionToOption(): bool;

    /**
     * Transitions the lease to the "Confirmed" (Bevestigd) state.
     *
     * This method moves the lease to the confirmed state, indicating that the rental agreement
     * has been officially approved and confirmed.
     *
     * @return bool
     */
    public function transitionToConfirmed(): bool;

    /**
     * Transitions the lease to the "Completed" (Afgesloten) state.
     *
     * This method transitions the lease to the completed state, marking the rental process as
     * finalized with no further actions required.
     *
     * @return bool
     */
    public function transitionToCompleted(): bool;

    /**
     * Transitions the lease to the "Cancelled" (Geannuleerd) state.
     *
     * This method cancels the lease and moves it to the cancelled state, ending the process
     * without completing the rental agreement.
     *
     * @param  CancellationDataObject $cancellationDataObject  The data object that holds all the information for a lease request cancellation.
     * @return bool
     */
    public function transitionToCancelled(CancellationDataObject $cancellationDataObject): bool;

    /**
     * Transitions the current state to "Archived."
     *
     * This method is implemented by state machine classes that support transitioning
     * an entity to an "Archived" state. It performs any necessary state validation and
     * triggers state-related actions required to archive the entity.
     *
     * Implementers of this method should ensure the transition process meets any
     * business rules associated with archiving (e.g., permission checks or status validation)
     * and handles any cleanup or notifications relevant to the transition.
     *
     * @return void
     */
    public function transitionToArchived(): void;
}
