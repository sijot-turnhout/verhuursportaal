<?php

declare(strict_types=1);

namespace App\Filament\Resources\TenantResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

final class NotesRelationManager extends RelationManager
{
    /**
     * The declaration of the relation that will be used in the relation manager.
     */
    protected static string $relationship = 'notes';

    /**
     * The singular name for the model defenition.
     */
    protected static ?string $modelLabel = 'Notitie';

    /**
     * The plural model name definition for the relation manager.
     */
    protected static ?string $pluralModelLabel = 'Notities';

    /**
     * The panel title declaration for the panel.
     */
    protected static ?string $title = 'Notities';

    /**
     * Method to initiate the form to create of edit notes in the relation manager.
     *
     * @param  Form $form The form builder that will be used to render the form in the relation manager.
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Titel')->required()->maxLength(255)->columnSpan(8),
                Forms\Components\Textarea::make('body')->label('Notitie')->columnSpan(12)->rows(6),
            ])->columns(12);
    }

    /**
     * Method to render the data table in the view.
     *
     * @param  Table $table The table builder that will be used to render the data table.
     */
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-book-open')
            ->emptyStateHeading('Geen notities gevonden voor de huurder')
            ->emptyStateDescription('Momenteel zijn er geen notities opgeslagen voor de huurder in het systeem. Om te starten met notities kunt u er simpel weg een aanmaken.')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('author.name')->label('Ingevoegd door'),
                Tables\Columns\TextColumn::make('title')->label('Titel')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Ingevoegd op')->sortable()->since(),
                Tables\Columns\TextColumn::make('updated_at')->label('Laast gewijzigd op')->sortable()->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
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
}
