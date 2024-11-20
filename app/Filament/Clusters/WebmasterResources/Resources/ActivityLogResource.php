<?php

namespace App\Filament\Clusters\WebmasterResources\Resources;

use App\Filament\Clusters\WebmasterResources;
use App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Pages;
use App\Models\Activity;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $modelLabel = 'Logboek';
    protected static ?string $pluralModelLabel = 'Logboek';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $cluster = WebmasterResources::class;
    protected static ?string $navigationGroup = 'Monitoring';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Uitgevoerd door')
                    ->translateLabel()
                    ->searchable(),

                Tables\Columns\TextColumn::make('log_name')
                    ->label('Categorie')
                    ->icon('heroicon-o-tag')
                    ->badge()
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Handeling')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Gebeurtenis')
                    ->translateLabel()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uitgevoerd op')
                    ->translateLabel()
                    ->date()
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Bekijk')
                    ->slideOver(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListActivityLogs::route('/')];
    }
}
