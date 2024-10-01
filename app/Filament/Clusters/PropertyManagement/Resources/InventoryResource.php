<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Pages;
use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas\InventoryArticleForm;
use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas\InventoryArticlesTable;
use App\Models\Articles;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Class InventoryResource
 *
 * This resource manages the CRUD operations for articles in the inventory system.
 * It defines the structure for the form used to create and edit articles, as well
 * as the table used to display them. The resource integrates with Filament's
 * form and table components to create a user-friendly interface for managing articles.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources
 */
final class InventoryResource extends Resource
{
    /**
     * The Eloquent model that this resource manages.
     *
     * This defines the model that interacts with the database. In this case, it is the `Articles` model.
     *
     * @var string|null
     */
    protected static ?string $model = Articles::class;

    /**
     * The label used for a single instance of the model in the UI.
     *
     * This defines the human-readable name for an individual article that appears in the UI.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Artikel';

    /**
     * The label used for the navigation item.
     *
     * This defines how the resource will appear in the application's navigation panel.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Artikelen';

    /**
     * The label used for multiple instances of the model.
     *
     * This defines how the plural form of the model will be displayed in the UI,
     * especially in contexts where multiple records are listed or managed.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Artikelen';

    /**
     * The icon used in the navigation panel.
     *
     * The icon helps users easily recognize the resource. The heroicon-o-circle-stack icon
     * has been selected for representing inventory.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    /**
     * Defines the cluster this resource belongs to.
     *
     * Filament organizes resources into clusters for better grouping and navigation.
     * This resource is part of the Property Management cluster.
     *
     * @var string|null
     */
    protected static ?string $cluster = PropertyManagement::class;

    /**
     * Defines the navigation group under which this resource will appear.
     *
     * This helps categorize resources under common groups in the navigation bar,
     * providing better user experience for locating resources.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = "Inventaris";

    /**
     * Configures the form schema for managing articles.
     *
     * This method sets up the fields and structure of the form that will be used to create or update articles.
     * It includes sections for detailed article information, with a description field for extra details.
     *
     * @param  Form $form  The form instance that will be used to build the UI.
     * @return Form        Returns the form schema with defined sections and fields.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('Artikel informatie'))
                    ->description(trans('Alle benodigde informatie omtrent het artikel dat word opgenomen in de inventaris.'))
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->collapsible()
                    ->compact()
                    ->columns(12)
                    ->schema(InventoryArticleForm::make()),
            ]);
    }

    /**
     * Provides a badge in the navigation that shows the count of articles.
     *
     * This method retrieves the count of articles stored in the inventory and displays it as a badge
     * next to the navigation item for quick reference.
     *
     * @return string|null  The count of articles, cast to a string.
     */
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    /**
     * Configures the table schema for displaying and managing articles.
     *
     * This method defines how the articles will be displayed in a table format. It includes
     * features like sortable columns, searchable fields, and bulk actions such as deletion.
     *
     * @param  Table $table  The table instance that will be used to build the UI.
     * @return Table         Returns the table schema with defined columns, actions, and bulk actions.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(trans('Geen artikelen gevonden'))
            ->emptyStateIcon('heroicon-o-circle-stack')
            ->emptyStateDescription(trans('Momenteel zijn er geen artikelen opgenomen in de inventaris. Gebruik de knop rechtboven om een artikel te registreren.'))
            ->columns(InventoryArticlesTable::make())
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make(trans('Artikel informatie'))
                ->icon('heroicon-o-information-circle')
                ->iconColor('primary')
                ->description('Alle informatie omtrent het artikel in de inventaris.')
                ->compact()
                ->columns(12)
                ->schema([
                ]),
        ]);
    }

    /**
     * Defines the routes for listing, creating, and editing inventory articles.
     *
     * This method maps the CRUD operations to their respective routes, enabling users to manage
     * articles through specific URLs.
     *
     * @return array  An array of route definitions for index, create, and edit pages.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'view' => Pages\ViewInventory::route('/{record}'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
