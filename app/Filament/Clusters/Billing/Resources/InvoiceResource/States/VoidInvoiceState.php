<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\InvoiceResource\States;

/**
 * Class VoidInvoiceState
 *
 * Represents the final state of an invoice where it is marked as "void."
 * In this state, the invoice is considered permanently inactive and cannot
 * transition to any other state. No further actions, such as payments or edits,
 * can be performed once the invoice is voided.
 *
 * This state is terminal, and any further state transitions will be blocked.
 *
 * @package App\Filament\Clusters\Billing\Resources\InvoiceResource\States
 */
final class VoidInvoiceState extends InvoiceState
{
    // No transitions or state changes are allowed from this state.
}
