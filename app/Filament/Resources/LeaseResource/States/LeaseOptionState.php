<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource\ValueObjects\CancellationDataObject;
use Illuminate\Support\Facades\DB;

/**
 * Class LeaseOptionState
 *
 * This class represents the "Option" state in the lease lifecycle. It extends the base `LeaseState` class,
 * and is used when a lease is in the optional reservation phase, meaning the rental is temporarily reserved
 * and awaiting further confirmation.
 *
 * You can override methods from the parent `LeaseState` class to define behavior and state transitions specific
 * to the optional reservation phase of the lease.
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
final class LeaseOptionState extends LeaseState
{
    /**
     * {@inheritDoc}
     */
    public function transitionToCancelled(CancellationDataObject $cancellationDataObject): bool
    {
        return DB::transaction(function(): bool {
            $this->lease->setStatus(LeaseStatus::Cancelled)
                ->registerCancellation($cancellationDataObject->getReason())
        });
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToConfirmed(): bool
    {
        return $this->lease->markAs(LeaseStatus::Confirmed);
    }
}
