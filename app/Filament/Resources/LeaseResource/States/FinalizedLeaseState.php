<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

/**
 * FinalizedLeaseState Class
 *
 * Represents the state of a lease that has been finalized. This class extends the base LeaseState class
 * and provides the specific implementation for a lease that has been completed, with all terms fulfilled.
 *
 * In this state, the lease agreement has concluded, and all parties have fulfilled their obligations.
 * This state indicates that the lease lifecycle is complete and no further actions are required under
 * the lease agreement.
 *
 * @see tests/Feature/LeaseResource/States/FinalizedLeaseStateTest.php
 */
final class FinalizedLeaseState extends LeaseState {}
