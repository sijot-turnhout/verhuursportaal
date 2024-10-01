<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\ArticleCategoryResource\Pages;
use App\Filament\Resources\ArticleCategoryResource\RelationManagers\ArticlesRelationManager;
use App\Models\ArticleCategory;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Resource class for managing Article Categories in the Property Management cluster.
 *
 * This class defines the form schema, table configuration, and available pages
 * for managing article categories. It integrates with Filament's admin panel
 * and follows the resource-driven approach of managing database entities.
 *
 * @todo Implementation of the authorization policy.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources
 */
final class ArticleCategoryResource extends Resource
{
    /**
     * The model associated with this resource.
     *
     * @var string|null
     */
    protected static ?string $model = ArticleCategory::class;

    /**
     * The icon to be displayed in the navigation sidebar for this resource.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    /**
     * The singular label for the model, used in places like page titles.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'label';

    /**
     * The label for the resource as it appears in the navigation menu.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Categorie labels';

    /**
     * Defines the cluster or section this resource belongs to in the application.
     *
     * Clusters help group related resources together under one navigation group.
     *
     * @var string|null
     */
    protected static ?string $cluster = PropertyManagement::class;

    /**
     * The group under which this resource appears in the navigation sidebar.
     *
     * Resources in Filament can be grouped together by setting this value, which helps
     * to organize the admin panel.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = "Inventaris";

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ComponentsSection::make(trans('Categorie label informatie'))
                    ->icon('heroicon-o-tag')
                    ->iconColor('primary')
                    ->description('Alle nodige informatie omtrent het categorie label dat word gekoppeld aan de inventaris artikelen')
                    ->collapsible()
                    ->compact()
                    ->columns(12)
                    ->schema([
                        TextEntry::make('name')->translateLabel()->label('Naam')->columnSpan(9),
                        TextEntry::make('created_at')->label('Aangemaakt op')->translateLabel()->columnSpan(3),
                        TextEntry::make('description')->label('Beschrijving')->translateLabel()->columnSpan(12)->placeholder(trans('- geen beschrijving of extra informatie opgegeven')),
                    ]),
            ]);
    }

    /**
     * Defines the form schema for creating or editing an Article Category.
     *
     * This method returns a form with sections and fields that allow users
     * to input a new category name and optionally provide a description.
     * The form is customizable and uses Filament's form components.
     *
     * @param  Form $form  The form builder instance.
     * @return Form        The form instance with the defined schema.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->heading(trans('Categorie label toevoegen'))
                    ->description(trans('Gebruik dit formulier om een nieuw categorie label toe te voegen aan het inventarissysteem. Vul de naam van de categorie in en, indien nodig, een korte beschrijving om duidelijk te maken welke items onder deze categorie vallen.'))
                    ->icon('heroicon-o-tag')
                    ->iconColor('primary')
                    ->columns(12)
                    ->compact()
                    ->schema([
                        TextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(9)
                            ->translateLabel(),

                        Textarea::make('description')
                            ->label('Beschrijving')
                            ->columnSpan(12)
                            ->rows(4)
                            ->placeholder(trans('Extra informatie omtrent de artikel categorie in de inventaris')),
                    ]),
            ]);
    }

    /**
     * Defines the table columns, filters, and actions for listing Article Categories.
     *
     * This method allows you to customize how the list of `ArticleCategory` records
     * will appear in the admin panel. You can define columns, actions, and filters
     * to be displayed in the table view.
     *
     * @param  Table $table  The table builder instance.
     * @return Table         The table instance with the defined configuration.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(trans('Geen categorieen gevonden'))
            ->emptyStateDescription(trans('Het lijkt erop dat er momenteel geen inventaris artikel categorieen zijn gevonden. gebruik de knop rechtboven om er een categorie te registreren.'))
            ->emptyStateIcon('heroicon-o-tag')
            ->columns([
                TextColumn::make('name')->label('Naam')->sortable()->searchable()->weight(FontWeight::Bold)->color('primary'),
                TextColumn::make('articles_count')->label(trans('Aantal artikelen'))->counts('articles')->sortable(),
                TextColumn::make('description')->label('Beschrijving')->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation(),
            ]);
    }

    public static function getRelations(): array
    {
        return [ArticlesRelationManager::class];
    }

    /**
     * Defines the pages available for managing Article Categories.
     *
     * Filament uses a page-based system where each resource can have multiple pages,
     * such as listing records, creating a new record, or editing an existing record.
     *
     * @return array  The array of pages with their routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticleCategories::route('/'),
            'create' => Pages\CreateArticleCategory::route('/create'),
            'view' => Pages\ViewArticleCategory::route('/{record}'),
            'edit' => Pages\EditArticleCategory::route('/{record}/edit'),
        ];
    }
}
