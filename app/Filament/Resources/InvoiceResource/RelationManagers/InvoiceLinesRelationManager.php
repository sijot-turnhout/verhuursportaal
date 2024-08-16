<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoiceLines';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Item/Dienstverlening')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(8),
                Forms\Components\TextInput::make('quantity')
                    ->label('aantal')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('unit_price')
                    ->label('eenheidsprijs')
                    ->numeric()
                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                    ->required()
                    ->columnSpan(2),
                Forms\Components\Toggle::make('type')
                    ->label('Dit item is een vermindering op de factuur')
                    ->onColor('success')
                    ->offColor('danger')
                    ->columnSpan(12),
            ])->columns(12);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(trans('Facturatie regels'))
            ->emptyStateHeading(trans('Geen facturatieregels toegevoegd'))
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateDescription('Het lijkt er dat er momenteel nog geen facturatieregels zijn toegevoegd op de factuur. Gebruik de knop "item toevoegen" om een regel toe te voegen.')
            ->columns([
                Tables\Columns\TextColumn::make('id')->placeholder('-')->label('#'),
                Tables\Columns\TextColumn::make('type')->label('Regel type')->badge()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('facturatie item')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('quantity')->label('aantal')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('unit_price')->label('eenheidsprijs')->sortable()->money('EUR'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('totaalbedrag')
                    ->sortable()
                    ->money('EUR')
                    ->weight(FontWeight::Bold),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label(trans('item toevoegen'))
                    ->modalHeading(__('item toevoegen')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Regel types')
                    ->options(BillingType::class),
            ]);
    }
}
