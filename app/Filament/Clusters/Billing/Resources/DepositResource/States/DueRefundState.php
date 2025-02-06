<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\States;

/**
 * Class DueRefundState
 *
 * This class represents the "Due refund state" in the deposit lifecycle.
 * It extends the base `PaymentState` class, and it typically used as transitioning state, where the refund of a deposit is over the deadline.
 * Specific behaviours aand state transitions related to the due refund state can be implemented by overriding methods from the parent class, `paymentState`
 *
 *  @package App\Filament\Clusters\Billing\Resources\DepositResource\States
 */
final class DueRefundState extends PaymentState {}
