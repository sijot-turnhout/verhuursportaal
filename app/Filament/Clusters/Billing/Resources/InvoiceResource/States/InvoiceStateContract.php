<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\InvoiceResource\States;

/**
 * Interface InvoiceStateContract
 *
 * Defines the contract for all invoice states within the billing system. Each
 * state must implement specific transitions and behaviors that are applicable to
 * its lifecycle. This ensures consistency and allows invoices to transition between
 * different states in a predictable manner.
 *
 * The primary method in this contract, `transitionToOpen()`, should handle the
 * logic for transitioning an invoice to the "open" state, where it becomes ready
 * for processing or payment.
 *
 * @package App\Filament\Clusters\Billing\Resources\InvoiceResource\States
 */
interface InvoiceStateContract
{
    /**
     * Transition the invoice to the "open" state.
     *
     * This method should implement the logic necessary to transition an invoice
     * to the "open" state. This transition typically marks the invoice as active
     * and prepares it for payment, with associated changes to its attributes such
     * as setting a due date.
     *
     * @return bool  Indicates whether the transition was successful.
     */
    public function transitionToOpen(): bool;

    /**
     * Transition the invoice to the "paid" state.
     *
     * This method should implement the logic necessary to transition an invoice
     * to the "paid" state. This transition signifies that the invoice has been
     * settled, which may involve updating the status and any related financial
     * records.
     *
     * @return bool  Indicates whether the transition was successful.
     */
    public function transitionToPaid(): bool;

    /**
     * Transition the invoice to the "void" state.
     *
     * This method should implement the logic necessary to transition an invoice
     * to the "void" state. This transition indicates that the invoice is no longer
     * valid and should not be processed for payment.
     *
     * @return bool  Indicates whether the transition was successful.
     */
    public function transitionToVoid(): bool;

    /**
     * Transition the invoice to the "uncollected" state.
     *
     * This method should implement the logic necessary to transition an invoice
     * to the "uncollected" state. This transition typically signifies that the
     * invoice has not been paid by the due date, prompting further actions for
     * collection.
     *
     * @return bool  Indicates whether the transition was successful.
     */
    public function transitionToUncollected(): bool;
}
