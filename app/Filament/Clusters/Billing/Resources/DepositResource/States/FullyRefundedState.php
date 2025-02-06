<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\States;

/**
 * Represent the 'FullyRefunded' state for a lease deposit in the state machine.
 *
 * This class defines the behaviour and attributes specific to deposits that have been transitioned to an partially refunded state.
 * It extends the base PaymentState, allowing it for encapsulation of any unique rules, actions, or properties that apply exclusively to fullr refunded deposits.
 *
 * Typically, deposits in the fully refunded state are no longer in an active state or modifiable, although they remain accessible for historical or reporting purposes.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\States
 */
final class FullyRefundedState extends PaymentState {}
