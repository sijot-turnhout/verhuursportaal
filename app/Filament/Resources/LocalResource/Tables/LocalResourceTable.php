<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Tables;

use App\Filament\Resources\LocalResource\Enums\Status;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class LocalResourceTable
 *
 * This class defines the table structure and actions for displaying `Local` records in the Filament admin panel.
 * It includes the columns to be displayed, such as name, storage location, issue count, description, and the last
 * updated timestamp. It also sets up actions for editing, deleting individual records, and bulk actions for deleting
 * multiple records.
 *
 * @package App\Filament\Resources\LocalResource\Tables
 */
final readonly class LocalResourceTable
{
    /**
     * Configure and return the table instance with the specified columns, actions, and bulk actions.
     *
     * @param  Table $table  The table builder instance to configure.
     * @return Table         The configured table instance.
     */
    public static function make(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-home-modern')
            ->emptyStateHeading('Geen lokalen gevonden')
            ->emptyStateDescription(trans('Het lijkt erop dat er momenteel nog geen lokalen geregistreerd zijn in de applicatie.'))
            ->columns([
                TextColumn::make('name')
                    ->label('Lokaal')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),

                IconColumn::make('storage_location')->label('Opslag locatie')
                    ->boolean(),

                TextColumn::make('issues_count')
                    ->label('Aantal werkpunten')
                    ->counts([
                        'issues' => fn(Builder $query): Builder => $query->whereNot('status', Status::Closed),
                    ])
                    ->badge()
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('warning'),

                TextColumn::make('description')
                    ->label('Beschrijving / Extra informatie')
                    ->searchable()
                    ->placeholder('(niets opgegeven)'),

                TextColumn::make('updated_at')
                    ->label('Laast bijgewerkt')
                    ->date(),
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
