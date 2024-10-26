<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\InvoiceResource\States;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;

final class UncollectedInvoiceState extends InvoiceState
{
    public function transitionToPaid(): bool
    {
        return $this->invoice->status(
            attributes: ['status' => InvoiceStatus::Paid, 'due_at' => now()]
        );
    }
}
