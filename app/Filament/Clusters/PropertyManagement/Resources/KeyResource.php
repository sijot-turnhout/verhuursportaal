<?php

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums\KeyTypes;
use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Pages;
use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\RelationManagers;
use App\Models\Key;
use App\Models\Local;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KeyResource extends Resource
{
    protected static ?string $model = Key::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $modelLabel = 'Sleutel';

    protected static ?string $pluralModelLabel = 'Sleutels';

    protected static ?string $cluster = PropertyManagement::class;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                TextEntry::make('user.name')->label('Bewaarder')->columnSpan(4)->icon('heroicon-o-user-circle')->iconColor('primary'),
                TextEntry::make('name')->label('Naam v/d sleutel')->columnSpan(8)->icon('heroicon-o-key')->iconColor('primary'),
                TextEntry::make('type')->label('Type')->columnSpan(4)->badge(),
                IconEntry::make('is_master_key')->label('Loper')->columnSpan(4)->boolean(),
                TextEntry::make('key_number')->label('Serienummer')->columnSpan(4),
                TextEntry::make('locals.name')->badge()->columnSpan(6)->label('Geeft toegang tot')->icon('heroicon-o-home')->default('geen lokalen gekoppeld')->iconColor('primary'),
                TextEntry::make('description')->label('Beschrijving/Extra informatie')->placeholder('Geen beschrijving of extra informatie opgegeven')->columnSpan(12),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(trans('Toegewijzen aan'))
                    ->translateLabel()
                    ->columnSpan(6)
                    ->relationship(name: 'user', titleAttribute: 'name'),

                Forms\Components\Select::make('type')
                    ->label(trans('Sleutel type'))
                    ->translateLabel()
                    ->options(KeyTypes::class)
                    ->required()
                    ->columnSpan(6),

                Forms\Components\TextInput::make('key_number')
                    ->label(trans('Serienummer'))
                    ->translateLabel()
                    ->columnSpan(6),

                Forms\Components\TextInput::make('name')
                    ->label(trans('Naam v/d sleutel'))
                    ->translateLabel()
                    ->required()
                    ->columnSpan(6),

                Forms\Components\Select::make('spaces')
                    ->label('Geeft toegang tot')
                    ->translateLabel()
                    ->multiple()
                    ->relationship('locals', 'name')
                    ->options(fn() => Local::query()->pluck('name', 'id'))
                    ->columnSpan(12),

                Forms\Components\Textarea::make('description')
                    ->label(trans('Beschrijving/Extra informatie'))
                    ->rows(3)
                    ->columnSpan(12),

                Forms\Components\Toggle::make('is_master_key')
                    ->columnSpan(12)
                    ->label('Deze sleutel is een loper')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading(trans('Geen sleutels geregistreerd'))
            ->emptyStateDescription(trans('Momenteel zijn er nog geen sleutels geregistreerd in de applicatie'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Bewaarder')->translateLabel()->searchable(),
                Tables\Columns\IconColumn::make('is_master_key')->label('Loper')->translateLabel()->searchable()->boolean(),
                Tables\Columns\TextColumn::make('type')->label('Type')->translateLabel()->searchable()->badge(),
                TextColumn::make('name')->label('Naam v/d sleutel')->translateLabel()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Sleutelregistratie bekijken')
                    ->modalIcon('heroicon-o-eye')
                    ->modalIconColor('primary')
                    ->modalDescription('Alle geregistreerde gegevens omtrent de sleutel die is toegewezen aan de gebruiker in de applicatie')
                    ->slideOver(),

                Tables\Actions\EditAction::make()
                    ->modalHeading('Sleutelregistratie aanpassen')
                    ->modalDescription('Weergave voor het weijzigen van een sleutelregistratie in de applicatie.')
                    ->modalIcon('heroicon-o-pencil-square')
                    ->slideOver(),

                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Sleutelregistratie verwijderen')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKeys::route('/'),
        ];
    }
}
