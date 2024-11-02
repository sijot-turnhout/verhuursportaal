<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Traits;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource\Pages\ListLeases;
use App\Models\Lease;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

/**
 * Trait UsesArchivingSystemActions
 *
 * This trait provides reusable methods for handling archiving-related actions in Filament tables.
 * It includes actions for soft-deleting (archiving), restoring, and force-deleting leases
 * in both individual and bulk formats. Additionally, the visibility of these actions is
 * controlled based on the active tab, ensuring that certain actions appear only in the
 * appropriate context (e.g., archive-related actions only appear in the archive tab).
 *
 * @package App\Filament\Resources\LeaseResource\Traits
 */
trait UsesArchivingSystemActions
{
    /**
     * Icon representing the archive action.
     *
     * This icon is used in the UI to signify that an item can be archived.
     * Displaying this icon helps users quickly identify and understand the
     * action of archiving within the application's interface.
     *
     * @var string $archiveIcon Icon displayed for the archive action.
     */
    private static string $archiveIcon = 'heroicon-o-archive-box';

    /**
     * Icon representing the unarchive action.
     *
     * This icon is used in the UI to indicate that an item can be unarchived,
     * providing a clear visual cue for reversing an archive action.
     * It enhances the user experience by helping users quickly identify the option
     * to restore items from an archived state.
     *
     * @var string $unarchiveIcon Icon displayed for the unarchive action.
     */
    private static string $unarchiveIcon = 'heroicon-o-archive-box-x-mark';

    /**
     * Defines an action for archiving a lease record.
     *
     * This method configures the archive action, allowing users to mark a lease as archived.
     * The action requires confirmation to ensure the user intends to proceed, as archiving
     * is irreversible. It includes a modal with a warning message, custom icon, and success
     * notification upon completion. Visibility is controlled by a policy check to ensure
     * the user has permission to archive the lease.
     *
     * @return Action The configured archive action.
     */
    protected static function archiveAction(): Action
    {
        return Action::make($label ?? trans('archiveren'))
            ->icon($icon ?? self::$archiveIcon)
            ->requiresConfirmation()
            ->modalHeading(trans('Verhuring archiveren'))
            ->modalIcon('heroicon-o-archive-box')
            ->color('danger')
            ->modalDescription(trans('De archivering van een verhuring kan niet ongedaan worden gemaakt. Dus wees zeker of je deze handeling wilt uitvoeren.'))
            ->visible(fn (Lease $lease): bool => Gate::allows('archive', $lease))
            ->action(fn(Lease $lease) => $lease->state()->transitionToArchived())
            ->successNotificationTitle(trans('De verhuring is geachiveerd.'));
    }

    /**
    * Creates a bulk action for archiving multiple leases.
    *
    * This bulk action allows authorized users to archive selected leases in bulk.
    * It ensures that the action is only visible on specific tabs (typically where leases
    * are in cancellable or finalized status), confirms the action with the user, and
    * provides feedback upon success or failure. Once confirmed, each selected lease's
    * state transitions to "Archived."
    *
    * @param  string|null $label  Optional label for the bulk action button. Defaults to "Archiveren."
    * @param  string|null $icon   Optional icon for the bulk action button. Defaults to archive icon.
    * @return BulkAction          Configured bulk action for archiving leases.
    */
    protected static function archiveBulkAction(?string $label = null, ?string $icon = null): BulkAction
    {
        return BulkAction::make($label ?? trans('Archiveren'))
            ->icon($icon ?? self::$archiveIcon)
            ->visible(fn(ListLeases $livewire): bool => $livewire->activeTab === '6' || $livewire->activeTab === '5')
            ->deselectRecordsAfterCompletion()
            ->requiresConfirmation()
            ->color('danger')
            ->modalHeading(trans('Geselecteerde verhuringen archiveren'))
            ->modalDescription(trans('De archivering van de geselecteerde verhuringen kan niet ongedaan worden gemaakt. Dus wees zeker dat je deze handeling wilt uitvoeren.'))
            ->successNotificationTitle(trans('De items zijn toegevoegd aan het archief.'))
            ->failureNotificationTitle(trans('Kon de items niet toevoegen aan het archief.'))
            ->action(function (Lease $model, Collection $selectedRecords): void {
                $selectedRecords->each(function (Lease $selectedRecord) {
                    $selectedRecord->state()->transitionToArchived();
                });
            });
    }
}
