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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeyResource extends Resource
{
    protected static ?string $model = Key::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $modelLabel = 'Sleutel';

    protected static ?string $pluralModelLabel = 'Sleutels';

    protected static ?string $cluster = PropertyManagement::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(trans('Sleutel informatie'))
                    ->description(trans('Alle benodigde informatie om een sleutel te registreren en beheren in de applicatie'))
                    ->icon('heroicon-m-key')
                    ->iconColor('primary')
                    ->columns(12)
                    ->compact()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label(trans('Toegewijzen aan'))
                            ->translateLabel()
                            ->columnSpan(3)
                            ->relationship(name: 'user', titleAttribute: 'name'),

                        Forms\Components\Select::make('type')
                            ->label(trans('Sleutel type'))
                            ->translateLabel()
                            ->options(KeyTypes::class)
                            ->required()
                            ->columnSpan(3),

                        Forms\Components\TextInput::make('key_number')
                            ->label(trans('Serienummer'))
                            ->translateLabel()
                            ->columnSpan(3),

                        Forms\Components\TextInput::make('name')
                            ->label(trans('Naam v/d sleutel'))
                            ->translateLabel()
                            ->required()
                            ->columnSpan(3),

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
                    ])
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'create' => Pages\CreateKey::route('/create'),
            'edit' => Pages\EditKey::route('/{record}/edit'),
        ];
    }
}
