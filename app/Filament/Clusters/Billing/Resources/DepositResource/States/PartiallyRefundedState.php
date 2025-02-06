<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\States;

/**
 * Represents the 'PartiallyRefunded' state for a lease deposit in the state machine.
 *
 * This class defines behaviour and attributes specific to deposits that have been transitioned to an partially refunded state.
 * It extends the base PaymentState, allowing for encapsulation of any unique rules, actions, or properties that apply exclusively to Pärtially refunded deposits.
 *
 * Typically, deposits in the partially refunded state are no longer in an active state of modifiable, although they remain accessible for historical or reporting purposes.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\States
 */
final class PartiallyRefundedState extends PaymentState {}
