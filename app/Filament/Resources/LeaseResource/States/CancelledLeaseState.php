<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

/**
 * CancelledLeaseState Class
 *
 * Represents the state of a lease that has been cancelled. This class extends the base LeaseState class
 * and provides the specific implementation for a lease that is no longer active.
 *
 * This state indicates that the lease agreement has been terminated, either by mutual agreement or due
 * to other factors such as a breach of contract or failure to meet necessary conditions.
 * Once a lease enters this state, it is generally considered closed and inactive.
 *
 * @see tests/Feature/LeaseResource/States/CancelledLeaseStateTest.php
 */
final class CancelledLeaseState extends LeaseState {}
