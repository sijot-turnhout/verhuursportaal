<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use App\Models\BillingItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Class InvoiceLinesRelationManager
 *
 * Manages the relation between an invoice and its associated invoice lines.
 * This class provides the configuration for displaying and managing invoice lines
 * through the Filament resource management system.
 *
 * @package App\Filament\Resources\InvoiceResource\RelationManagers;
 */
final class InvoiceLinesRelationManager extends RelationManager
{
    /**
     * The relationship name for the invoice lines.
     *
     * @var string
     */
    protected static string $relationship = 'invoiceLines';

    /**
     * Configures the form schema for creating or editing invoice lines.
     *
     * This method defines the form fields and their properties for managing
     * invoice lines, including fields for item name, quantity, unit price, and
     * whether the item is a discount on the invoice.
     *
     * @param  Form $form  The form instance to configure.
     * @return Form        The configured form instance.
     */
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
                Forms\Components\Textarea::make('description')
                    ->label(trans('Beschrijving van de facturatieregel'))
                    ->columnSpan(12)
                    ->rows(3),
                Forms\Components\Toggle::make('type')
                    ->label('Dit item is een vermindering op de factuur')
                    ->onColor('success')
                    ->offColor('danger')
                    ->columnSpan(12),
            ])->columns(12);
    }

    /**
     * Configures the table schema for displaying invoice lines.
     *
     * This method defines the table columns and actions for listing and managing
     * invoice lines, including columns for item ID, type, name, quantity, unit price,
     * and total price, as well as actions for creating, editing, and deleting invoice lines.
     *
     * @param  Table $table  The table instance to configure.
     * @return Table         The configured table instance.
     */
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
                Tables\Columns\TextColumn::make('name')
                    ->label('facturatie item')
                    ->searchable()
                    ->sortable()
                    ->description(fn(BillingItem $billingItem): ?string => $billingItem->description),
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
                    ->label(trans('Facturatieregel toevoegen'))
                    ->modalHeading(__('Facturatieregel toevoegen'))
                    ->modalIcon('heroicon-o-currency-euro')
                    ->modalDescription('Hier kunt een dienstverlening of item toevoegen dat zal worden aangerekend aan de huurder tijdens zijn verhuring'),
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
