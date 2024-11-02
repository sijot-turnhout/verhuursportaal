<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;

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
final class LeaseFinalizedState extends LeaseState
{
    /**
     * Transitions the lease to the "Archived" state.
     *
     * This method updates the lease's status to indicate that it has been archived.
     * Once archived, the lease is considered inactive and is typically excluded from
     * further modifications or active status lists, although it remains accessible
     * for historical reference.
     *
     * This method assumes that any preconditions for archiving (such as status checks
     * or authorization) have already been validated by the caller.
     *
     * @return void
     */
    public function transitionToArchived(): void
    {
        $this->lease->update(['status' => LeaseStatus::Archived]);
    }
}
