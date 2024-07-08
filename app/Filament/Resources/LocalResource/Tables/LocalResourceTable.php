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

final readonly class LocalResourceTable
{
    public static function make(Table $table): Table
    {
        return $table
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
