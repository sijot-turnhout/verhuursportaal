<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\RelationManagers\KeyManagementRelationManager;
use App\Filament\Resources\LocalResource\Forms\LocalResourceForm;
use App\Filament\Resources\LocalResource\Pages;
use App\Filament\Resources\LocalResource\RelationManagers\IssuesRelationManager;
use App\Filament\Resources\LocalResource\Tables\LocalResourceTable;
use App\Models\Local;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * Class LocalResource
 *
 * This class defines the resource for managing `Local` entities in the Property Management system.
 * It handles the configuration of forms, tables, pages, and relations for interacting with `Local` records.
 *
 * @todo sijot-turnhout/verhuursportaal#19 - Proper unit test suite for the LocalResource class
 * @todo sijot-turnhout/verhuursportaal#20 - Herlocatie van de LocalResource class
 *
 * @package App\Filament\Resources
 */
final class LocalResource extends Resource
{
    /**
     * The Eloquent model used by this resource.
     *
     * This property specifies the `Local` model, representing a `Local` (location) entity in the database.
     * The model is used to fetch, create, update, and delete records from the `locals` table.
     *
     * @var string|null
     */
    protected static ?string $model = Local::class;

    /**
     * Singular name for the resource displayed in views.
     *
     * This is the human-readable singular label of the resource, used in the backend UI.
     * It represents a single `Local` entity, referred to as "Lokaal".
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Lokaal';

    /**
     * Navigation icon for the resource in the admin panel.
     *
     * The icon that will be used in the navigation menu of the application backend.
     * Here, the `heroicon-o-home-modern` icon is used to represent `Local` entities visually.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    /**
     * Plural label for the resource displayed in views.
     *
     * This is the human-readable plural label for the resource, used to refer to multiple `Local` entities.
     * It is displayed in the backend UI as "Lokalen".
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Lokalen';

    /**
     * The cluster (group) of resources that this resource belongs to.
     *
     * This specifies that the `LocalResource` belongs to the `PropertyManagement` cluster.
     * Clusters are used to group related resources in the admin panel for better organization.
     *
     * @var class-string<\Filament\Clusters\Cluster>|null
     */
    protected static ?string $cluster = PropertyManagement::class;

    /**
     * Configure the form used to create or edit a `Local` record.
     *
     * This method delegates the form rendering to `LocalResourceForm::render()`,
     * which returns a `Form` object containing the fields and layout for creating or editing a `Local` entity.
     *
     * @para   Form  $form  The form instance used to build the form fields and layout.
     * @return Form         The configured form instance.
     */
    public static function form(Form $form): Form
    {
        return LocalResourceForm::render($form);
    }

    /**
     * Configure the table used to display the list of `Local` records.
     *
     * This method delegates the table configuration to `LocalResourceTable::make()`,
     * which defines the columns, filters, and actions available for listing `Local` entities in the UI.
     *
     * @param  Table $table  The table instance used to build the columns and layout.
     * @return Table         The configured table instance.
     */
    public static function table(Table $table): Table
    {
        return LocalResourceTable::make($table);
    }

    /**
     * Define the relations that are available in this resource.
     *
     * This method defines the relationship managers available for the `Local` resource.
     * Currently, it specifies the `IssuesRelationManager`, allowing the user to manage related issues.
     *
     * @return array<int, class-string>  The array of relation managers used in this resource.
     */
    public static function getRelations(): array
    {
        return [
            IssuesRelationManager::class,
            KeyManagementRelationManager::class,
        ];
    }

    /**
     * Defines the information list schema for displaying general room details.
     * This structure is used to present information about a room in a clear and organised way.
     *
     * @param  Infolist $infolist The infolist object to configure.
     * @return Infolist           The configured infolist object with the defined columns.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(trans('Algemene informatie'))
                    ->icon('heroicon-o-home-modern')
                    ->iconColor('primary')
                    ->compact()
                    ->columns(12)
                    ->description('De algemene informatie over het lokaal die nodig is om het lokaal goed te beheren en of te documenteren.')
                    ->schema([
                        TextEntry::make('name')->columnSpan(4)->label('Naam')->translateLabel(),
                        IconEntry::make('storage_location')->columnSpan(2)->label('Opslaglocatie')->translateLabel(),
                        TextEntry::make('created_at')->icon('heroicon-o-clock')->iconColor('primary')->columnSpan(3)->label('Aangemaakt op')->translateLabel()->date(),
                        TextEntry::make('updated_at')->icon('heroicon-o-clock')->iconColor('primary')->columnSpan(3)->label('Laatst gewijzigd op')->translateLabel()->date(),
                        TextEntry::make('description')->columnSpan(12)->label('Beschrijving/Extra informatie')->translateLabel()->placeholder(trans('- Geen extra informatie of beschrijving opgegeven')),
                    ]),
            ]);
    }

    /**
     * Define the pages that implement the resource's functionality.
     *
     * This method defines the pages used in the resource, including:
     *
     * - `index`: The main list view for displaying all `Local` records.
     * - `create`: The form for creating a new `Local` record.
     * - `edit`: The form for editing an existing `Local` record.
     *
     * Each page is associated with a specific route and page class.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>  An array mapping page routes to page classes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocals::route('/'),
            'create' => Pages\CreateLocal::route('/create'),
            'view' => Pages\ViewLocal::route('/{record}'),
            'edit' => Pages\EditLocal::route('/{record}/edit'),
        ];
    }
}
