<?php

declare(strict_types=1);

namespace App\Filament\Clusters\WebmasterResources\Resources;

use App\Filament\Clusters\WebmasterResources;
use App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Filters\DateRangeFilter;
use App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Pages;
use App\Filament\Widgets\ActivityRegistrationChart;
use App\Models\Activity;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

/**
 * Resource class for managing the activity log.
 *
 * This class provides a comprehensive Filament resource for interacting with the application's activity log, which is a record of actions performed within the system.
 * It uses the `Activity` model to represent individual log entries, and this resource defines how that data is presented, filtered, and interacted with within the Filament admin panel.
 *
 * Key features of this resource include:
 *
 * - Table View:    A configurable table displays a list of activity log entries, allowing for sorting, searching, and filtering by various criteria.
 * - Detailed View: Provides a detailed view of individual log entries, showing specific information about the action performed.
 * - Row Actions:   Actions that can be performed on individual log entries, such as viewing more details.
 * - Bulk Actions:  Actions that can be performed on multiple selected log entries, such as exporting the selected data.
 * - Filtering:     Allows filtering of log entries by date range and other criteria.
 * - Widgets:       Integrates with Filament widgets to provide visualizations and summarized information about the activity log data (e.g., charts).
 *
 * This resource is designed to provide administrators with a powerful tool for monitoring and analyzing system activity.
 *
 * @package App\Filament\Clusters\WebmasterResources\Resources
 */
final class ActivityLogResource extends Resource
{
    /**
     * The Eloquent model class name associated with this resource.
     * This specifies the model that represents individual activity log entries in the database?
     * It is used for retrieving, displaying, and manipulating activity log data.
     *
     * @var string|null
     */
    protected static ?string $model = Activity::class;

    /**
     * The singular label used to refer to this resource in the UI.
     * This label used when referring to a single log entry.
     * It provides a user-friendly name for the resource in the Filament admin panel.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = "Logboek";

    /**
     * The plural label used to refer to this resource in the UI.
     * This label is used when referring in multiple activity log entries.
     * It provides a user-friendly name for the resource in the Filament admin panel.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = "Logboek";

    /**
     * The icon used to represent this resource in the navigation menu of the Filament admin panel.
     * This uses the name of a Heroicon SVG icon.
     * The icon provides a visual cue for the resource in the navigation.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = "heroicon-o-book-open";

    /**
     * The cluster this resource belongs to.
     * Clusters are used to group related resources together in the Filament navigation menu, improving organization and usability for administrators.
     * This property should be set to the fully qualified class name of the cluster.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = WebmasterResources::class;

    /**
     * The navigation group this resource belongs to.
     * Navigation groups provide another level of organization within the Filament navigation menu, allowing related resources to be grouped under a common heading.
     * This can further enhance the usability of the admin panel, particularly for applications with many resources.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = "Monitoring";

    /**
     * Configures the table view for listing activity log entries.
     * This method defines the structure and appearance of the activity log table within the Filament admin panel.
     * It specifies which columns are displayed, what actions are available for each row, any bulk actions that can be performed on selected rows,
     * and any filters that can be applied to the data. This allows for a highly customized and interactive table display for managing the activity log.
     *
     * @param  Table  $table The Filament Table instance to be configured. This instance provides a fluent interface for adding columns, actions, and other table features.
     * @return Table         The configured Table instance, ready to be rendered in the Filament admin panel.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-pencil-square')
            ->emptyStateHeading(trans('Geen registraties gevonden'))
            ->emptyStateDescription(trans('Momenteel zijn er nog geen logboek registraties aangemaakt, of gevonden onder de matchende criteria'))
            ->columns(self::tableColumnsLayout())
            ->actions(self::tableRecordActions())
            ->bulkActions(self::headerActions())
            ->filters([DateRangeFilter::register()]);
    }

    /**
     * Retrieves an array of page configurations for this resource.
     * Pages represent different views or sections within the resource, such as the "index" page for listing entries or a "create" page for adding new entries.
     * This method defines the routes and associated components for each page within the resource.
     *
     * Returns an array of page configurations, where each key represents a page name (e.g., "index") and the value is a route definition using Filament's routing conventions.
     *
     * {@inheritDoc}
     */
    public static function getPages(): array
    {
        return ["index" => Pages\ListActivityLogs::route("/")];
    }

    /**
     * Configures the infolist view for displaying detailed information about a single activity log entry.
     * The infolist provides a structured way to present key-value pairs and other details about the selected log entry.
     * This method defines the layout and content of the infolist, including which fields are displayed, their labels, and any associated icons or formatting.
     *
     * @param  Infolist  $infolist  The Filament Infolist instance to be configured. This instance provides methods for adding entries and customizing the layout of the infolist.
     * @return Infolist             The configured Infolist instance, ready to display detailed information about the activity log entry.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                TextEntry::make('causer.name')->label('Uitgevoerd door')->translateLabel()->icon('heroicon-o-user-circle')->iconColor('primary')->default(trans('Systeem gebruiker'))->columnSpan(6),
                TextEntry::make('created_at')->label('Uitgevoerd op')->translateLabel()->icon('heroicon-o-clock')->iconColor('primary')->columnSpan(6),
                TextEntry::make('log_name')->label('Categorie')->translateLabel()->icon('heroicon-o-tag')->badge()->columnSpan(6),
                TextEntry::make('event')->label('Handeling')->translateLabel()->columnSpan(6),
                TextEntry::make('description')->label('Gebeurtenis')->translateLabel()->columnSpan(12),
                KeyValueEntry::make('properties')->label('Extra waarden')->keyLabel('Sleutel')->valueLabel('Waarde')->translateLabel()->columnSpan(12),
            ]);
    }

    /**
     * Defines the widgets to be displayed on this resource's page.
     * Widgets can provide visualizations, summaries, or other interactive elements related to the resource data.
     * This method returns an array of widget class names, allowing for customization of the information presented alongside the main resource view.
     *
     * Returns an array of fully qualified widget class names. Each class name should correspond to a Filament widget component.
     *
     * @return array<int, class-string>
     */
    public static function getWidgets(): array
    {
        return [ActivityRegistrationChart::class];
    }

    /**
     * Defines the actions available for individual records in the activity log table.
     * These actions allow users to interact with specific log entries directly from the table view.
     * Common actions include viewing details, editing, or deleting a log entry.
     * This method configures the appearance and behavior of these row actions.
     *
     * Returns an array of action configurations, each defining a specific action that can be performed on a table row.
     * These configurations use Filament's table action builder methods to define the action's label, icon, and behavior.
     *
     * @return array<int, Tables\Actions\ViewAction>
     */
    private static function tableRecordActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()->label("Bekijk")
                ->slideOver()
                ->modalHeading(trans('Geregistreerde activiteit'))
                ->modalDescription(trans('Alle benodigde informatie voor het bekijken van de geregistreerde activiteit in de applicatie'))
                ->modalIconColor('primary')
                ->modalIcon('heroicon-o-information-circle'),
        ];
    }

    /**
     * Defines the actions available in the header of the activity log table.
     * These actions typically operate on multiple selected entries, such as bulk export or deletion.
     * This method configures which bulk actions are available and how they behave.
     *
     * Returns an array of bulk action configurations, each defining a specific action that can be performed on selected rows in the table.
     * These configurations use Filament's table bulk action builder methods.
     *
     * @return array<int, ExportBulkAction>
     */
    private static function headerActions(): array
    {
        return [ExportBulkAction::make()];
    }

    /**
     * Defines the layout and configuration of columns displayed in the activity log table.
     * This method specifies which data fields are displayed as columns, their labels, any associated icons,
     * whether they are searchable or sortable, and other display properties.
     * This allows for precise control over the presentation of activity log data in the table view.
     *
     * Returns an array of column configurations, each defining a single column in the table.
     * These configurations utilize Filament's table column builder methods to specify the
     * column's data source, label, formatting, and interactive features.
     *
     * @return array<int, Tables\Columns\TextColumn>
     */
    private static function tableColumnsLayout(): array
    {
        return [
            Tables\Columns\TextColumn::make("causer.name")
                ->label("Uitgevoerd door")
                ->default(trans('Systeem gebruiker'))
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->translateLabel()
                ->searchable(),

            Tables\Columns\TextColumn::make("log_name")
                ->label("Categorie")
                ->icon("heroicon-o-tag")
                ->badge()
                ->translateLabel()
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make("event")
                ->label("Handeling")
                ->translateLabel()
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make("description")
                ->label("Gebeurtenis")
                ->translateLabel()
                ->searchable(),

            Tables\Columns\TextColumn::make("created_at")
                ->label("Uitgevoerd op")
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->translateLabel()
                ->date(),
        ];
    }
}
