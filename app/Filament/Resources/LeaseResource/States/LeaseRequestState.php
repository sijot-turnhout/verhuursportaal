<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource\ValueObjects\CancellationDataObject;
use Illuminate\Support\Facades\DB;

/**
 * Class LeaseRequestState
 *
 * This class represents the "Lease Request" state in the lease lifecycle. It extends the base `LeaseState` class,
 * and is typically used when the lease is in the initial "request" phase, where a new lease application has been submitted.
 *
 * Specific behaviors and state transitions related to the lease request can be implemented by overriding methods from
 * the parent class, `LeaseState`.
 *
 * @package App\Filament\Resoources\LeaseResource\States
 */
final class LeaseRequestState extends LeaseState
{
    /**
     * {@inheritDoc}
     */
    public function transitionToQuotationRequest(): bool
    {
        return $this->lease->markAs(LeaseStatus::Quotation);
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToOption(): bool
    {
        return $this->lease->markAs(LeaseStatus::Option);
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToConfirmed(): bool
    {
        return $this->lease->markAs(LeaseStatus::Confirmed);
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToCancelled(CancellationDataObject $cancellationDataObject): bool
    {
        return DB::transaction(function () use ($cancellationDataObject): bool {
            $status = LeaseStatus::Cancelled;
            $auditMessage = trans('Heeft de status van een verhuring gewijzigd naar :status', ['status' => $status->getLabel()]);

            return $this->lease->setStatus(newStatus: $status, auditMessage: $auditMessage)->registerCancellation($cancellationDataObject->getReason());
        });
    }
}
