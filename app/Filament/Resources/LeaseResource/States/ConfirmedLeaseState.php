<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

/**
 * ConfirmedLeaseState Class
 *
 * Represents the state of a lease that has been confirmed. This class extends the base LeaseState class
 * and provides the specific implementation for a lease that has been formally agreed upon by all parties.
 *
 * In this state, the lease agreement is binding, and all terms have been accepted. This state indicates
 * that the lease is ready to be executed, and any preliminary conditions or prerequisites have been satisfied.
 */
final class ConfirmedLeaseState extends LeaseState {}
