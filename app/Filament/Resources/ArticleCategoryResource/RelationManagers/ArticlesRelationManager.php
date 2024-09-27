<?php

namespace App\Filament\Resources\ArticleCategoryResource\RelationManagers;

use Filament\Forms;
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
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(9),
            ]);
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
            ->recordTitleAttribute('name')
            ->columns([
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
