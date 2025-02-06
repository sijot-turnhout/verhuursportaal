<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\States;

/**
 * Represents the "Withdrawn" state for a lease deposit in the state machine.
 *
 * This class defines behaviour and attributs specific to deposits that have been transitioned to an withdrawn state.
 * It extends the base PaymentState, allowing for encapsulation of any unique rules, actions, or properties that apply exclusively to archived leases.
 *
 * Typically, deposits in the withdrawn state are no longer in an active state of modifiable, although they remain accessible for historical or reporting purposes.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\States
 */
final class WithdrawnState extends PaymentState {}
