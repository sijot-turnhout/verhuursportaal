<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Infolists\IssueInformationInfolist;
use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages;
use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Support\IssueOverviewTable;
use App\Models\Issue;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Resource class for managing issues within the property management cluster.
 *
 * The `IssueResource` class defines how issues are managed in the backend of the application.
 * This resource handles the display, creation, editing, and deletion of issues. It integrates
 * with the Filament Admin Panel to provide a user-friendly interface for managing the underlying
 * `Issue` model. This class includes configurations for the resource's navigation properties,
 * form schema, table schema, and page routes.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources
 */
final class IssueResource extends Resource
{
    /**
     * The Eloquent model that this resource represents.
     *
     * This property defines the model class associated with this resource. The resource
     * interacts with this model to perform CRUD operations and display data.
     *
     * @var class-string<\App\Models\Issue>|null
     */
    protected static ?string $model = Issue::class;

    /**
     * The icon used in the navigation menu for this resource.
     *
     * Specifies the icon that will appear next to this resource in the application's
     * backend navigation. The icon should be a valid Heroicons name.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    /**
     * The singular label used for this resource in the UI.
     *
     * This label is used when referring to a single instance of the resource in the
     * application's user interface.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Werkpunt';

    /**
     * The plural label used for this resource in the UI.
     *
     * This label is used when referring to multiple instances of the resource in the
     * application's user interface.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Werkpunten';

    /**
     * The navigation group in which this resource is grouped.
     *
     * Defines the group under which this resource will appear in the application's
     * backend navigation menu. Resources can be grouped to provide better organization
     * in the UI.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = 'Problemen & verbeteringen';

    /**
     * The resource cluster where this resource belongs.
     *
     * Specifies the cluster (a collection of related resources) that this resource is part of.
     * Clustering resources helps in organizing them logically within the application.
     *
     * @var string|null
     */
    protected static ?string $cluster = PropertyManagement::class;

    /**
     * Configures the infolist for the resource.
     *
     * This method defines the infolist layout for this resource, providing a detailed
     * view of the information related to an issue. It utilizes the `IssueInformationInfolist`
     * class to generate the infolist structure, which may include various details and attributes
     * of the `Issue` model.
     *
     * An infolist is typically used in Filament to display detailed information about a resource
     * record, often in a read-only format. This is particularly useful for displaying data without
     * the need for direct editing.
     *
     * @param  Infolist $infolist   The infolist instance being configured.
     * @return Infolist             The configured infolist instance.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return IssueInformationInfolist::make($infolist);
    }

    /**
     * Defines the table schema used for listing resource records.
     *
     * This method returns a Table instance that defines the columns and layout used
     * when displaying a list of resource records in the application. The schema array can
     * include various types of columns, such as text columns, badge columns, date columns, etc.
     *
     * @todo GH #14 - Refactoring van de open/close acties voor de werkpunten in de applicatie.
     *
     * @param  Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(IssueOverviewTable::columns())
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->emptyStateHeading('Geen werkpunten gevonden')
            ->emptyStateDescription(trans('Momenteel zijn er geen werkpunten gevonden in het systeem. Om een werkpunt aan te maken kunt u naar het betrefferende lokaal gaan en op de knop "werkpunt" aanmaken.'))
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->modalCancelAction(false)
                    ->extraModalFooterActions([
                        Tables\Actions\Action::make('Werkpunt afsluiten')
                            ->visible(fn(Issue $issue): bool => auth()->user()->can('close', $issue))
                            ->action(fn(Issue $issue) => $issue->state()->transitionToClosed())
                            ->color('danger')
                            ->icon('heroicon-o-document-check'),

                        Tables\Actions\Action::make('Werkpunt heropenen')
                            ->visible(fn(Issue $issue): bool => auth()->user()->can('reopen', $issue))
                            ->action(fn(Issue $issue) => $issue->state()->transitionToOpen())
                            ->color('gray')
                            ->icon('heroicon-o-arrow-path'),
                    ]),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Returns the badge value displayed in the navigation for this resource.
     *
     * This method calculates and returns the value that will be shown as a badge
     * next to the resource name in the navigation menu. Typically, this could be a count
     * of records or any other meaningful number.
     *
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    /**
     * Returns the color of the navigation badge for this resource.
     *
     * This method determines the color of the badge displayed next to the resource
     * name in the navigation menu, based on a condition such as the number of records.
     * For example, the badge may turn 'danger' (red) if the count exceeds a threshold.
     *
     * @return string|null
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'danger' : 'warning';
    }

    /**
     * Defines the pages available for this resource.
     *
     * This method returns an array mapping each page name (e.g., 'index', 'create') to
     * the respective route. This is used to generate the necessary routes for resource
     * actions like listing, creating, editing, and viewing records.
     *
     * @return array<string, \Filament\Pages\Page>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIssues::route('/'),
            'edit' => Pages\EditIssue::route('/{record}/edit'),
        ];
    }
}
