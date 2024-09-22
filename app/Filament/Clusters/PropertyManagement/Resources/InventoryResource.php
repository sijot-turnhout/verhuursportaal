<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Pages;
use App\Models\Inventory;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $modelLabel = 'Inventaris';

    protected static ?string $pluralModelLabl = 'Inventaris';

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $cluster = PropertyManagement::class;

    protected static ?string $navigationGroup = "Inventaris";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('Artikel informatie'))
                    ->description(trans('Alle benodigde informatie omtrent het artikel dat word opgenomen in de inventaris.'))
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->collapsible()
                    ->compact()
                    ->columns(12)
                    ->schema([
                        Textarea::make('description')
                            ->label(trans('Beschrijving / Extra informatie'))
                            ->rows(4)
                            ->columnSpan(12),
                    ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(trans('Geen inventaris items gevonden'))
            ->emptyStateIcon('heroicon-o-circle-stack')
            ->emptyStateDescription(trans('Momenteel zijn er geen artikelen opgenomen in de inventaris. Gebruik de knop rechtboven om een artikel te registreren.'))
            ->columns([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
