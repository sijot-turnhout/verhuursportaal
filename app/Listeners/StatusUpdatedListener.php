<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatusUpdated;

/**
 * Class StatusUpdatedListener
 *
 * This listener handles the StatusUpdated event. When the status is updated,
 * it logs the activity if a log message is provided.
 *
 * @package App\Listeners
 */
final readonly class StatusUpdatedListener
{
    /**
     * Handle the event.
     *
     * This method is triggered when the StatusUpdated event is fired. It logs
     * the status change activity if a log message is present.
     *
     * @param  StatusUpdated $statusUpdated The event instance containing the status update details.
     * @return void
     */
    public function handle(StatusUpdated $statusUpdated)
    {
        if (is_null($statusUpdated->logMessage)) {
            return;
        }

        activity(trans('verhuringen'))
            ->performedOn($statusUpdated->model)
            ->event('statuswijziging')
            ->causedBy(auth()->user() ?? null)
            ->withProperties(['oldStatus' => $statusUpdated->oldStatus, 'newStatus' => $statusUpdated->newStatus])
            ->log($statusUpdated->logMessage);
    }
}
