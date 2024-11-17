<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions;

use App\Enums\LeaseStatus;
use App\Models\Lease;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

/**
 * Assign the authenticated user as the supervisor of a lease.
 *
 * This action empowers users to take responsibility for lease management while ensuring
 * proper conditions are met. By preventing assignment on finalized or archived leases,
 * it safeguards data integrity and fosters efficient collaboration.
 *
 * @package App\Filament\Resources\LeaseResource\Actions
 */
final class AssignAuthenticatedUserAction extends Action
{
    /**
     * Initialize the action with a customizable name and default settings.
     *
     * This action is a gateway to proactive lease management, allowing users
     * to seamlessly assign themselves to leases requiring attention.
     *
     * @param  string|null $name Optional custom action name.
     * @return static
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'assign-authenticated-user-to-lease')
            ->label('Deze verhuring opvolgen')
            ->translateLabel()
            ->icon('heroicon-o-user-plus')
            ->visible(fn (Lease $lease): bool => self::checkIfAssignmentIsPossible($lease))
            ->action(fn (Lease $lease) => self::performSupervisorAssignment($lease));
    }

    /**
     * Check if the authenticated user can be assigned as the lease supervisor.
     *
     * This method ensures that assignments are allowed only for leases
     * that are active, open, and without an existing supervisor. It's a
     * robust filter to maintain workflow efficiency and prevent conflicts.
     *
     * @param  Lease $lease  The lease being evaluated for assignment.
     * @return bool          Whether assignment is allowed.
     */
    private static function checkIfAssignmentIsPossible(Lease $lease): bool
    {
        return $lease->status->notIn(enums: [LeaseStatus::Archived, LeaseStatus::Cancelled, LeaseStatus::Finalized])
            && $lease->supervisor()->doesntExist();
    }

    /**
     * Assign the authenticated user as the supervisor for the specified lease.
     *
     * By associating the current user with the lease, this method brings clarity
     * and ownership to the process. A success notification is triggered to
     * confirm the action, promoting transparency and accountability.
     *
     * @param  Lease $lease The lease for which the user is being assigned.
     * @return void
     */
    private static function performSupervisorAssignment(Lease $lease): void
    {
        $lease->supervisor()->associate(auth()->user());
        $lease->save();

        self::notifyAssignmentSuccess();
    }

    /**
     * Notify the user of a successful supervisor assignment.
     *
     * A well-timed notification enhances user experience by providing immediate
     * feedback. This message confirms the assignment and encourages proactive
     * engagement with lease tasks.
     *
     * @return void
     */
    private static function notifyAssignmentSuccess(): void
    {
        Notification::make()
            ->title(trans('Je bent nu aangewezen als opvolger voor de verhuring'))
            ->success()
            ->send();
    }
}
