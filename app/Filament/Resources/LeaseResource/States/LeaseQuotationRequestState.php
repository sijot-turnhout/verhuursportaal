<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

/**
 * Class LeaseQuotationRequestState
 *
 * This class represents the "Quotation Request" state in the lease lifecycle. It extends the base `LeaseState` class,
 * and is typically used when a lease is in the quotation request phase, meaning a rental offer or quote is being prepared.
 *
 * Override specific methods from the parent `LeaseState` class to define the behavior and state transitions
 * specific to this phase of the lease process.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
final class LeaseQuotationRequestState extends LeaseState {}
