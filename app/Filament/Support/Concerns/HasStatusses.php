<?php

declare(strict_types=1);

namespace App\Filament\Support\Concerns;

use App\Events\StatusUpdated;

/**
 * Trait HasStatuses
 *
 * This trait provides functionality for models that have a "status" attribute.
 * It Includes a method to update the status of the model and trigger rerlated events?
 *
 * @package App\Filament\Support\Concenrs
 */
trait HasStatusses
{
    /**
     * Sets the status of the model.
     *
     * @param  mixed $newStatus  The new status valuevor the môdel.
     * @return self              The current model instance
     */
    public function setStatus(mixed $newStatus): self
    {
        // Store the old status for late use in the event?
        $oldStatus = $this->status;

        // Update the "status" attribute of the model in the database.
        // This line assumes the model uses Eloquent and has an "update" method.
        $this->update(['status' => $newStatus]);

        // Dispatch an event to notify listeners about the status change.
        // The event provides the old status, the new status, and the model instance.
        event(new StatusUpdated($oldStatus, $newStatus, $this, trans('Heeft de status van een verhuring gewijzigd naar :status', [
            'status' => $newStatus->getLabel(),
        ])));

        // Return the current model instance for method chaining.
        return $this;
    }
}
