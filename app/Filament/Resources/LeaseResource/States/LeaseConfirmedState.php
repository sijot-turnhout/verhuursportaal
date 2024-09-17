<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

/**
 * Class LeaseConfirmedState
 *
 * This class represents the "Confirmed" state in the lease lifecycle. It extends the base `LeaseState` class
 * and is used when a lease has been officially confirmed, indicating that the rental agreement has been finalized
 * and approved by all necessary parties.
 *
 * Methods from the parent `LeaseState` class can be overridden to define the specific behavior and transitions
 * for the confirmed state of the lease.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
final class LeaseConfirmedState extends LeaseState {}
