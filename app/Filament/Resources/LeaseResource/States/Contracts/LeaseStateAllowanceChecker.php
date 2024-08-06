<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States\Contracts;

/**
 * LeaseStateAllowanceChecker Interface
 *
 * This interface defines the contract for checking if transitions between different lease states are allowed.
 * It provides methods to determine whether a lease can transition to specific states based on business logic.
 *
 * Implementing classes should define the logic to determine if a transition is permissible under current conditions.
 */
interface LeaseStateAllowanceChecker
{
    /**
     * Determine if the lease can transition to the Quotation or Option state.
     *
     * @return bool Returns true if the transition to Quotation/Option is allowed, false otherwise.
     */
    public function allowTransitionToQuotationOption(): bool;

    /**
     * Determine if the lease can transition to the Option state.
     *
     * @return bool Returns true if the transition to Option is allowed, false otherwise.
     */
    public function allowTransitionToOption(): bool;

    /**
     * Determine if the lease can transition to the Confirmed state.
     *
     * @return bool Returns true if the transition to Confirmed is allowed, false otherwise.
     */
    public function allowTransitionToConfirmed(): bool;

    /**
     * Determine if the lease can transition to the Cancelled state.
     *
     * @return bool Returns true if the transition to Cancelled is allowed, false otherwise.
     */
    public function allowTransitionToCancelled(): bool;

    /**
     * Determine if the lease can transition to the Finalized state.
     *
     * @return bool Returns true if the transition to Finalized is allowed, false otherwise.
     */
    public function allowTransitionToFinalized(): bool;
}
