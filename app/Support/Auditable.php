<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Auditable
 *
 * This trait provides a method for registering audut logs for action performed on Eloquent models.
 * It integrates with the spatie/activity-log logging system, allowing you to track changes or actions on models.
 * including the user who initiated the action (if available).
 *
 * @package App\Support
 */
trait Auditable
{
    /**
     * Registers an audit log entry with a specific log name and event type.
     *
     * This method records an audit entry in the activity log, allowing you to track actions performed on a model.
     * It optionally accepts a custom log name and event type to categorize the log entries. If no event type is provided,
     * the default behavior is to log the action without specifying an event. The currently authenticated user is used as the one who caused the action.
     *
     * @param  string|null $event        An optional event name that describes the type of action (e.g., 'created', 'updated').
     * @param  Model       $performedOn  The model instance on which the action was performed.
     * @param  string      $auditEntry   A description of the audit entry or the action that took place.
     * @return void
     */
    public function registerAuditEntry(?string $event = null, Model $performedOn, string $auditEntry): void
    {
        activity()
            ->causedBy(auth()->user())
            ->event($event)
            ->performedOn($performedOn)
            ->log($auditEntry);
    }
}
