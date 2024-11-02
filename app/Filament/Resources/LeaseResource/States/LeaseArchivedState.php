<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

/**
 * Represents the "Archived" state for a lease in the state machine.
 *
 * This class defines behavior and attributes specific to leases that have been
 * transitioned to an archived status. It extends the base LeaseState, allowing
 * for the encapsulation of any unique rules, actions, or properties that apply
 * exclusively to archived leases.
 *
 * Typically, leases in the archived state are no longer active or modifiable,
 * although they remain accessible for historical or reporting purposes.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
final class LeaseArchivedState extends LeaseState {}
