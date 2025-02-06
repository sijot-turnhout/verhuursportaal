<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\States;

/**
 * Class PaidState
 *
 * This class represent the "Paid state" in the deposit lifecycle.
 * It extends the base `PaymentState` class, and it typically used as entry state for now, where a new deposit registration has been submitted.
 * Specific behavours and state transitions related to the paid deposit can be implemented by overriding methods from the parent class, `paymentState`.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\States
 */
final class PaidState extends PaymentState {}
