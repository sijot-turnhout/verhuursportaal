<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\States;

/**
 * Interface PaymentStateContract
 *
 * This interface defines the contract for transitioning a lease deposit between various states.
 * Implementations of this interface shopuld handle the logic for transitioning a lease deposit to different
 * states, representing the lifecycle of a lease deposit in the application. Each method represents a specific
 * transition, providiing control over the lease deposit progression.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\States
 */
interface PaymentStateContract
{
    /**
     * Transitions the deposit to the "Paid" (betaald) state.
     *
     * This method initiates the transaction to the deposit state, which represents
     * the initial phase where the deposit is submitted and being paid.
     *
     * @return void
     */
    public function transitionToPaid(): void;

    /**
     * Transtions the deposit to "Revoked" (Ingetrokken).
     *
     * This method handles the transition to the Revoked state, where the deposit
     * has been withheld due to property damages and such.
     *
     * @return void
     */
    public function transitionToRevoked(): void;

    /**
     * Transitions the deposit to "Partially refunded" (deels ingetrokken).
     *
     * This method moves the paid deposit to the Partially refunded state. Indicated that
     * some amount of the deposit has been revoked due to property damages and such.
     *
     * @return void
     */
    public function transitionToPartiallyRefunded(): void;

    /**
     * Transitions the deposit from the paid state to the "Fully refunded" (volledig terugbetaakd) state.
     *
     * This method moves the deposit state from paid to the fully refunded state, indicated that there were no damages
     * during the lease. And the deposit is fully refunded to the tenant.
     *
     * @return void
     */
    public function transitionToFullyRefunded(): void;

    /**
     * Trainsition the deposit state from paid to the "Due refund" (terugbetaling verstreken) state.
     *
     * This method moves the paid deposit state from paid to due refund state, indicating that the refund handling hdate is due.
     * And priority actions are required by an authorized person.
     *
     * @return void
     */
    public function transitionToDueRefund(): void;
}
