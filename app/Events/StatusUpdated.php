<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a status update event for a model.
 *
 * This event is triggered when the status of a model changes.
 * It provides information about the old status, the new status, and the affected model.
 *
 * @package App\Events;
 */
final readonly class StatusUpdated
{
    /**
     * Creates a new StatusUpdated event instance.
     *
     * @param mixed  $oldStatus  The old status of the model.
     * @param mixed  $newStatus  The new status of the model.
     * @param Model  $model      The model instance that has been updated.
     * @param string $logMessage The message that needs to be recorded in the activity log.
     */
    public function __construct(
        public mixed $oldStatus,
        public mixed $newStatus,
        public Model $model,
        public ?string $logMessage = null,
    ) {}
}
