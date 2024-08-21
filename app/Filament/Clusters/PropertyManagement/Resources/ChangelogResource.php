<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Pages;
use App\Models\Changelog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Class ChangelogResource
 *
 * This resource manages the CRUD operations and UI for the Changelog model in the Property Management module.
 * It defines the forms, tables, and actions associated with changelogs, which are labeled as "Werklijst" in the UI.
 *
 * @todo Needs implementation of unit tests.
 */
final class ChangelogResource extends Resource
{
    /**
     * The model that this resource is associated with.
     *
     * @var string|null
     */
    protected static ?string $model = Changelog::class;

    /**
     * The icon used for the resource's navigation in the Filament admin panel.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    /**
     * The label used for the model in the UI, in singular form.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Werklijst';

    /**
     * The label used for the model in the UI, in plural form.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Werklijsten';

    /**
     * The navigation group in which this resource will be placed.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = 'Problemen & verbeteringen';

    /**
     * The cluster that this resource is part of, which organizes related resources.
     *
     * @var string|null
     */
    protected static ?string $cluster = PropertyManagement::class;

    /**
     * Defines the form schema for creatiung and editing changelogs.
     * This schema includes fields for title and any other necessary information.
     *
     * @param  Form $form  The form instance
     * @return Form        THe form configured with the schema for changelogs.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(trans('Algemene informatie van de werklijst'))
                    ->description(trans('Alle vereiste informatie die nodig is om een werklijst gerelateerd aan de lokalen en of materiaal aan te maken in het portaal'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->iconColor('primary')
                    ->compact()
                    ->columns(12)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titel')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(8),
                        Forms\Components\Select::make('user_id')
                            ->label('Verantwoordelijke')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->columnSpan(4),
                        Forms\Components\Textarea::make('description')
                            ->label('Beschrijving/Extra informatie')
                            ->placeholder('Extra gegevens omtrent de werklijst zoals bv een beschrijving of planning voor een werkdag op het domein.')
                            ->rows(5)
                            ->columnSpan(12),
                    ]),
            ]);
    }

    /**
     * Defines the table schema for displaying changelogs in a list view.
     * This schema includes columns, actions, and bulk action for the table.
     *
     * @param  Table $table  The table instance
     * @return Table         The table configured with the schema for changelogs.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->emptyStateHeading('Geen werklijsten gevonden')
            ->emptyStateDescription(trans('Momenteel zijn er geen werklijsten gevonden voor de lokalen met de matchende cirteria. Indien u er een wilt aanmaken kan dat met de bovenste hoek.'))
            ->columns([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Defines the pages that are available for this resource,
     * Including the index, create and edit pages.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>  The array of pages for this resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChangelogs::route('/'),
            'create' => Pages\CreateChangelog::route('/create'),
            'edit' => Pages\EditChangelog::route('/{record}/edit'),
        ];
    }
}
