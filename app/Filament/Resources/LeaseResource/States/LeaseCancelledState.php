<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;

/**
 * Class LeaseCancelledState
 *
 * This class represents the 'Cancelled' state in the lease lifecycle.
 */
final class LeaseCancelledState extends LeaseState
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
