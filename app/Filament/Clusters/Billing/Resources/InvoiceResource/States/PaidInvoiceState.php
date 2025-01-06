<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\InvoiceResource\States;

/**
 * Class PaidInvoiceState
 *
 * Represents the final state of an invoice when it has been fully paid.
 * Once an invoice is in this state, no further changes or transitions are permitted.
 * This state signifies the completion of the payment process, and the invoice is
 * considered settled.
 *
 * As this is a terminal state, no other state transitions can occur from here.
 *
 * @package App\Filament\Clusters\Billing\Resources\InvoiceResource\States
 */
final class PaidInvoiceState extends InvoiceState
{
    // No transitions or state changes are allowed from this state.
}
