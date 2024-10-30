<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\RelationManagers;

use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use App\Models\BillingItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

final class QuotationLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'quotationLines';

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
                Forms\Components\TextArea::make('description')
                    ->label('Beschrijving')
                    ->translateLabel()
                    ->columns(12)
                    ->cols(2)
                    ->columnSpan(12),
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
            ->heading('Offerte regels')
            ->emptyStateHeading('Geen offerteregels gevonden')
            ->emptyStateDescription('Momenteel zijn er nog geen tegels toegevoegd aan de offerte')
            ->columns([
                Tables\Columns\TextColumn::make('id')->placeholder('-')->label('#'),
                Tables\Columns\TextColumn::make('type')->label('Regel type')->badge()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('facturatie item')->searchable()->sortable()->description(fn(BillingItem $billingItem): ?string => $billingItem->description),
                Tables\Columns\TextColumn::make('quantity')->label('aantal')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('unit_price')->label('eenheidsprijs')->sortable()->money('EUR'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('totaalbedrag')
                    ->sortable()
                    ->money('EUR')
                    ->weight(FontWeight::Bold),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Regel types')
                    ->options(BillingType::class),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Offerteregel toevoegen')
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Offerte regel bewerken')
                    ->slideOver(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading("Offerte regel verwijderen"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
