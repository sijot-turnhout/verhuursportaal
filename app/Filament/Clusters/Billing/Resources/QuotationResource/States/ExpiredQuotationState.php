<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\States;

/**
 * ExpiredQuotationState
 *
 * This class represents the final state of a quotation that has expired.
 * It is a terminal state, meaning that once a quotation reaches this state,
 * no further actions can be performed on it, such as approving, editing, or sending it.
 *
 * Since it extends `BaseQuotationState`, it inherits basic state-related behaviors,
 * but any actions in this state are restricted due to the quotation's expiration.
 *
 * @package App\Filament\Clusters\Billing\Resources\QuotationResource\States
 */
final class ExpiredQuotationState extends BaseQuotationState
{
    // As a final state, no further modifications or actions are possible for the expired quotation.
}
