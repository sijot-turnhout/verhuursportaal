<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\InvoiceResource\States;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;

/**
 * Class DraftInvoiceState
 *
 * Represents the initial "Draft" state of an invoice, indicating that the invoice
 * is in preparation and has not yet been finalized or sent to the customer.
 *
 * In this state, the invoice can be transitioned to an "open" state, at which point
 * it becomes payable and acquires a due date.
 *
 * @package App\Filament\Clusters\Billing\Resources\InvoiceResource\States
 */
final class DraftInvoiceState extends InvoiceState
{
    /**
     * Transition the invoice to the "open" state.
     *
     * This method updates the status of the invoice to "open" and sets the
     * `due_at` date one month from the current date. This transition signifies
     * that the invoice is now ready to be paid and will expire at the specified
     * due date unless further action is taken.
     *
     * @return bool Returns `true` if the transition was successful, `false` otherwise.
     */
    public function transitionToOpen(): bool
    {
        return $this->invoice->update(
            attributes: ['status' => InvoiceStatus::Open, 'due_at' => now()->addMonth()->endOfDay()
        ]);
    }
}
