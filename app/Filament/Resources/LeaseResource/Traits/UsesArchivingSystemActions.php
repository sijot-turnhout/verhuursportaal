<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Traits;

use App\Filament\Resources\LeaseResource\Pages\ListLeases;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;

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
     * Default icon for archiving actions.
     *
     * @var string
     */
    protected static string $archivingIcon = 'heroicon-o-archive-box';

    /**
     * Creates a bulk action for permanently deleting selected records in the archive tab.
     * This action is only visible when viewing the archive tab and bypasses soft deletion,
     * permanently removing the records from the database.
     *
     * @param  string $resourceName  The name of the resource that u want to archive. Defaults to 'item'
     * @return ForceDeleteBulkAction The bulk force delete action.
     */
    protected static function forceDeleteBulkAction(string $resourceName = 'item'): ForceDeleteBulkAction
    {
        return ForceDeleteBulkAction::make()
            ->label(trans(':resource verwijderen', ['resource' => $resourceName]))
            ->modalHeading(trans(':resource permanent verwijderen', ['resource' => $resourceName]))
            ->modalDescription(trans('Indien u deze :resource verwijderd zal ook tevens alle gekoppelde data automatisch verwijderd worden. Deze actie kan niet meer ongedaan gemaakt worden', ['resource' => $resourceName]))
            ->translateLabel()
            ->visible(fn (ListLeases $livewire): bool => $livewire->activeTab === 'archive');
    }

    /**
     * Creates a bulk action for archiving (soft-deleting) selected records.
     * This action is visible when not in the archive tab and moves records to the archive.
     *
     * @param  string $resourceName  The name of the resource that u want to archive in bulk. Defaults to 'item'
     * @return DeleteBulkAction      The bulk soft delete (archive) action.
     */
    protected static function archiveBulkAction(string $resourceName = 'item'): DeleteBulkAction
    {
        return DeleteBulkAction::make()
            ->visible(fn (ListLeases $livewire): bool => $livewire->activeTab !== 'archive')
            ->label('Archiveren')
            ->color('gray')
            ->icon(self::$archivingIcon)
            ->modalIcon(self::$archivingIcon)
            ->modalIconColor('primary')
            ->modalHeading(trans(':item archiveren', ['item' => $resourceName]))
            ->modalDescription(trans('Weet je zeker dat je dit wilt doen? Bij het archiveren kan je de gegevens van de :item niet meer raadplegen.', ['item' => $resourceName]));
    }

    /**
     * Creates a bulk action for restoring selected records from the archive.
     * This action is only visible when viewing the archive tab and allows users to recover
     * previously archived records.
     *
     * @param  string $resourceName  The name of the resource that u want to use. Defaults to 'item'
     * @return RestoreBulkAction     The bulk restore action for archived records.
     */
    protected static function archiveRestoreBulkAction(string $resourceName = 'item'): RestoreBulkAction
    {
        return RestoreBulkAction::make()
            ->modalIconColor('primary')
            ->modalHeading()
            ->modalDescription()
            ->visible(fn (ListLeases $livewire): bool => $livewire->activeTab === 'archive');
    }

    /**
     * Groups the bulk actions related to archiving (force delete and restore) into one action group.
     * This provides users with a clear and organized way to handle archive-related actions.
     *
     * @param  string $resourceName  The name of the resource that u want to archive. Defaults to 'item'
     * @return BulkActionGroup       The action group containing archiving-related actions.
     */
    protected static function bulkArchivingActionGroup(string $resourceName = 'item'): BulkActionGroup
    {
        return BulkActionGroup::make([
            self::forceDeleteBulkAction($resourceName),
            self::archiveRestoreBulkAction($resourceName),
        ])
        ->label(trans('Archief acties'))
        ->icon('heroicon-o-cog-8-tooth');
    }

    /**
     * Creates an individual action for restoring a single archived record.
     * This action allows users to bring back a specific lease from the archive.
     *
     * @return RestoreAction The restore action for a single archived record.
     */
    protected static function restoreArchiveEntityAction(): RestoreAction
    {
        return RestoreAction::make()
            ->label('Herstellen')
            ->modalIconColor('primary')
            ->translateLabel();
    }

    /**
     * Creates an individual action for soft-deleting (archiving) a single record.
     * This action allows a single lease record to be moved to the archive.
     *
     * @param  string $resourceName  The name of the resource that u want to archive. Defaults to 'item'
     * @return DeleteAction          The archive (soft delete) action for a single record.
     */
    protected static function archiveEntityAction(string $resourceName = 'item'): DeleteAction
    {
        return DeleteAction::make()
            ->icon(self::$archivingIcon)
            ->modalIcon(self::$archivingIcon)
            ->modalHeading(trans(':item archiveren', ['item' => $resourceName]))
            ->modalDescription(trans('Weet je zeker dat je dit wilt doen? Bij het archiveren kan je de gegevens van een :item niet meer raadplegen.', ['item' => $resourceName]))
            ->label('Archiveren');
    }

    /**
     * Creates an individual action for permanently deleting a single record.
     * This action removes the record from the database permanently and bypasses the soft delete mechanism.
     *
     * @param  string $resourceName  The name of the resource that u are using. Defaults to 'item'
     * @return ForceDeleteAction     The force delete action for a single record.
     */
    protected static function forceDeleteEntityAction(string $resourceName = 'item'): ForceDeleteAction
    {
        return ForceDeleteAction::make()
            ->label(trans(':resource verwijderen', ['resource' => $resourceName]))
            ->modalHeading(trans(':resource permanent verwijderen', ['resource' => $resourceName]))
            ->modalDescription(trans('Indien u deze :resource verwijderd zal ook tevens alle gekoppelde data automatisch verwijderd worden. Deze actie kan niet meer ongedaan gemaakt worden', ['resource' => $resourceName]))
            ->label('Verwijderen');
    }
}
