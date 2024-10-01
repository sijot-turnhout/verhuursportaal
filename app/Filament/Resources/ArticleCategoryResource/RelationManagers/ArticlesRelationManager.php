<?php

namespace App\Filament\Resources\ArticleCategoryResource\RelationManagers;

use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas\InventoryArticleForm;
use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas\InventoryArticlesTable;
use App\Models\Articles;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Class ArticlesManager
 *
 * ArticlesRelationManager is responsible for managing the relationship
 * between article categories and their associated articles in the inventory system.
 *
 * This class allows for managing articles that belong to a specific category,
 * enabling actions such as creating, attaching, editing, and deleting articles
 * within the context of a category.
 *
 * @todo Refactor this relation manager to be part of the property management cluster.
 * @todo Implementatie idee: Het is misschioen handig om een soort id encoding systeem te hebben op de inventaris (articles).
 *
 * @package App\Filament\Resources\ArticleCategoryResource\RelationManagers
 */
final class ArticlesRelationManager extends RelationManager
{
    /**
     * The name of the relationship that this manager handles.
     *
     * This specifies the relationship method on the parent model (ArticleCategory)
     * that this relation manager is responsible for. It fetches and manages the associated articles.
     *
     * @var string
     */
    protected static string $relationship = 'articles';

    /**
     * The title displayed for the related articles section in the UI.
     *
     * This static property sets a custom title for the articles that are related
     * to the current resource. It can be used to display a more user-friendly label
     * when managing related records, such as in a relation manager or other components.
     *
     * @var string|null
     */
    protected static ?string $title = 'Gekoppelde artikelen';

    /**
     * Defines the form schema for creating or editing articles associated with the category.
     *
     * The form layout is split into 12 columns, with a required 'name' input field
     * that has a maximum length of 255 characters.
     *
     * @param  Form $form  The form instance to define the schema on.
     * @return Form        The configured form instance.
     */
    public function form(Form $form): Form
    {
        return  $form
            ->columns(12)
            ->schema(InventoryArticleForm::make());
    }

    /**
     * Determines if the relationship manager is in read-only mode.
     *
     * This method returns `false`, meaning the articles within the category
     * can be created, edited, or deleted.
     *
     * @return bool  Returns `false` to indicate that the relation is editable.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Defines the table schema for displaying the list of articles within a category.
     *
     * This method sets up table columns, header actions (like creating or attaching articles),
     * and row actions (editing and deleting records). It also supports bulk actions.
     *
     * @param  Table $table  The table instance to define the schema on.
     * @return Table         The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-circle-stack')
            ->emptyStateHeading('Geen artikelen gevonden')
            ->emptyStateDescription('Het lijkt erop dat er momenteel geen inventaris artikelen gekoppeld zijn aan het inventaris label.')
            ->columns(InventoryArticlesTable::make())
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->visible(Articles::query()->doesntHave('labels')->count() > 0)
                    ->icon('heroicon-o-link')
                    ->label(trans('Artikel koppelen'))
                    ->modalHeading(trans('Artikel koppelen'))
                    ->modalDescription(trans('Koppel een bestaand inventaris artikel aan dit label.'))
                    ->modalSubmitAction()
                    ->modalCloseButton(),

                Tables\Actions\CreateAction::make()
                    ->label(trans('artikel toevoegen'))
                    ->icon('heroicon-o-plus')
                    ->modalIconColor('info')
                    ->modalHeading(trans('Inventaris artikel toevoegen'))
                    ->modalDescription(trans('Gebruik dit formulier om een nieuw categorie label toe te voegen aan het inventarissysteem. Vul de naam van de categorie in en, indien nodig, een korte beschrijving om duidelijk te maken welke items onder deze categorie vallen.'))
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
