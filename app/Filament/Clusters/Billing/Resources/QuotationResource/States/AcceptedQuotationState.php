<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\States;

/**
 * Class AcceptedQuotationState
 *
 * Represents the final state in the quotation's lifecycle. Once a quotation reaches this state,
 * it has been accepted, and no further transitions are expected. This state signifies that the
 * quotation is considered finalized and can no longer be modified or cancelled.
 *
 * Since this is a final state, there are no follow-up states. Further actions, such as invoicing
 * or billing, may happen in separate workflows, but those are not part of the quotation's state
 * transitions.
 *
 * @package App\Filament\Clusters\Billing\Resources\QuotationResource\States
 */
final class AcceptedQuotationState extends BaseQuotationState
{
    // As a final state, no further modifications or actions are possible for the expired quotation.
}
