<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

/**
 * Class LeaseFinalizedState
 *
 * This class represents the "Finalized" state in the lease lifecycle. It extends the base `LeaseState` class
 * and is used when a lease has been completed and all obligations have been fulfilled, marking the end of the rental process.
 *
 * You can override methods from the parent `LeaseState` class to define specific behavior and transitions
 * related to the finalized phase of the lease.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
final class LeaseFinalizedState extends LeaseState {}
