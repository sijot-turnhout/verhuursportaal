<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\States;

/**
 * Class DeclinedQuotationState
 *
 * This class represents the "Declined" state of a quotation. Once a quotation reaches
 * this state, it is considered to be permanently declined by the client and no further
 * transitions can occur from this state. Being a final state, no additional state transitions
 * are allowed.
 *
 * A declined quotation indicates that the client has rejected the offer, and the quotation
 * will no longer be pursued.
 *
 * @package App\Filament\Clusters\Billing\Resources\QuotationResource\States
 */
final class DeclinedQuotationState extends BaseQuotationState
{
    // This state is final, meaning no further transitions are possible from here.
}
