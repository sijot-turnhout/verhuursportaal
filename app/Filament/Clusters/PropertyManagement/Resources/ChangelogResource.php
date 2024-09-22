<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Pages;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\RelationManagers\IssuesRelationManager;
use App\Models\Changelog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
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

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    /**
     * Configure the infolist schema for displaying changelog details.
     *
     * This method defines the structure of the infolist used to present a detailed view of a `Changelog` record.
     * It organizes the information into a collapsible, compact section, providing a quick overview of key details.
     * The infolist displays attributes such as the title, status, assigned user, and description.
     *
     * @param  Infolist $infolist  The infolist instance to be configured.
     * @return Infolist            The configured infolist instance with the defined schema.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make(trans('Werklijst informatie'))
                ->icon('heroicon-o-clipboard-document-list')
                ->iconColor('primary')
                ->description(trans('Hier krijgt u de belangrijkste informatie in één oogopslag. Of u nu op zoek bent naar basisgegevens of specifieke details, dit paneel helpt u snel en gemakkelijk de juiste informatie te vinden.'))
                ->columns(12)
                ->collapsible()
                ->compact()
                ->schema([
                    TextEntry::make('title')->label('Titel')->columnSpan(6),
                    TextEntry::make('status')->label('Status')->columnSpan(3)->badge(),
                    TextEntry::make('user.name')->label('Opgevolgd door')->placeholder('-')->columnSpan(3),
                    TextEntry::make('description')->label(trans('Beschrijving/extra informatie'))->placeholder('-')->columnSpan(12),
                ]),
        ]);
    }

    /**
     * Defines the form schema for creatiung and editing changelogs.
     * This schema includes fields for title and any other necessary information.
     *
     * @param  Form $form  The form instance
     * @return Form        The form configured with the schema for changelogs.
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
                            ->placeholder('-')
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
                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Opgevolgd door')->placeholder('-'),
                Tables\Columns\TextColumn::make('title')->label('Werklijst')->sortable()->searchable(),

            ])
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

    /**
     * Get the relation managers associated with this resource.
     *
     * This method returns an array of relation managers that define the relationships
     * of the current resource with other entities. Relation managers help in handling
     * related data such as displaying, creating, updating, or deleting associated models.
     *
     * @return array The list of relation managers for the resource.
     */
    public static function getRelations(): array
    {
        return [
            IssuesRelationManager::class,
        ];
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
            'view' => Pages\ViewChangelog::route('/{record}'),
            'edit' => Pages\EditChangelog::route('/{record}/edit'),
        ];
    }
}
