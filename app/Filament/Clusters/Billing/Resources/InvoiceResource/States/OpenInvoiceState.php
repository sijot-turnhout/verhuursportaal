<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\InvoiceResource\States;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;

/**
 * Class OpenInvoiceState
 *
 * Represents the "open" state of an invoice. In this state, the invoice is active
 * and awaiting payment. This class handles state-specific behaviors such as the
 * transition to the "open" state.
 *
 * @package App\Filament\Clusters\Billing\Resources\InvoiceResource\States
 */
final class OpenInvoiceState extends InvoiceState
{
    public function transitionToPaid(): bool
    {
        return $this->invoice->update(
            attributes: ['status' => InvoiceStatus::Paid, 'paid_at' => now()],
        );
    }

    public function transitionToUnCollected(): bool
    {
        return $this->invoice->update(
            attributes: ['status' => InvoiceStatus::Uncollected],
        );
    }

    public function transitionToVoid(): bool
    {
        return $this->invoice->update(
            attributes: ['status' => InvoiceStatus::Void, 'cancelled_at' => now()],
        );
    }
}
