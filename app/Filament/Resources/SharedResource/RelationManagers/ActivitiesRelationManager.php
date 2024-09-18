<?php

declare(strict_types=1);

namespace App\Filament\Resources\SharedResource\RelationManagers;

use App\Enums\UserGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

/**
 * Manages the activities relation (activity log) for the Lease resource within the Filament admin panel.
 *
 * This class handles the display and management of activities related to the Lease model.
 * It defines how the activities are shown in the Filament admin panel, including table columns,
 * badge display, and visibility based on user permissions and configuration.
 *
 * @package App\Filament\Resources\SharedResource\RelationManagers
 */
final class ActivitiesRelationManager extends RelationManager
{
    /**
     * Variable for defining the name of the relation that will be used in this relation manager.
     *
     * @var string
     */
    protected static string $relationship = 'activities';

    /**
     * Variable for registering a custom name to the panel in the relation manager.
     *
     * @var string|null
     */
    protected static ?string $title = 'Bewerkingshistoriek';

    /**
     * Retrieves the badge count for the activities relation.
     *
     * This method returns the count of activities associated with the owner record, which is used
     * to display a badge in the Filament admin panel.
     *
     * @param  Model   $ownerRecord  The owner record of the relation entity.
     * @param  string  $pageClass    The class name of the page that the relation manager is on.
     * @return string|null           The badge count or null if no activities are present.
     */
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->activities()->count();
    }

    /**
     * Retrieves the badge color for the activities relation.
     *
     * This method returns the color used for the badge in the Filament admin panel.
     *
     * @param  Model   $ownerRecord  The owner record of the relation entity.
     * @param  string  $pageClass    The class name of the page that the relation manager is on.
     * @return string|null           The badge color or null if no color is set.
     */
    public static function getBadgeColor(Model $ownerRecord, string $pageClass): ?string
    {
        return 'info';
    }

    /**
     * Determines whether the activities panel is visible based on user permissions and activity presence.
     *
     * This method checks if the activities panel should be visible for the given lease record.
     * It verifies if activity logging is enabled, if the user has the required permissions,
     * and if there are any activities associated with the lease.
     *
     * @param  Model   $ownerRecord  The owner record of the relation entity (in this case, the lease entity).
     * @param  string  $pageClass    The class name of the page that the relation manager is on.
     * @return bool                  True if the panel is visible, false otherwise.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return config('activitylog.enabled')
            && (auth()->user()->user_group === UserGroup::Webmaster || auth()->user()->user_group === UserGroup::Rvb)
            && $ownerRecord->activities->count() > 0;
    }

    /**
     * Defines the table configuration for displaying activities in the Filament admin panel.
     *
     * This method configures the table columns, bulk actions, and other table settings used
     * to display the activities related to the lease. It sets attributes like column labels,
     * sorting, and searchability.
     *
     * @param  Table  $table  The table instance used to configure the activities display.
     * @return Table          The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->bulkActions([ExportBulkAction::make()])
            ->columns([
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Uitgevoerd door')
                    ->translateLabel()
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->iconColor('primary')
                    ->icon('heroicon-o-user-circle'),

                Tables\Columns\TextColumn::make('event')
                    ->label('Handeling')
                    ->sortable()
                    ->translateLabel()
                    ->badge(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Beschrijving')
                    ->searchable()
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uitgevoerd op')
                    ->translateLabel()
                    ->sortable()
                    ->dateTime('d/m/Y - H:i:s')
                    ->iconColor('primary')
                    ->icon('heroicon-o-clock'),
            ]);
    }
}
