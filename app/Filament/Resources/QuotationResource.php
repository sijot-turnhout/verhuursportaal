<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\Billing;
use App\Filament\Clusters\Billing\Resources\QuotationResource\RelationManagers\QuotationLinesRelationManager;
use App\Filament\Resources\QuotationResource\Pages;
use App\Models\Invoice;
use App\Models\Quotation;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * @todo Docblock this class.
 * @todo Implement badge on the navigation item that counts the quotation requests
 */
final class QuotationResource extends Resource
{
    protected static ?string $cluster = Billing::class;

    protected static ?string $model = Quotation::class;

    protected static ?string $modelLabel = 'Offerte';

    protected static ?string $pluralModelLabel = 'Offertes';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Facturatie';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Offerte informatie')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->collapsible()
                    ->description('De algemene infromatie van de offerte. let wel op alleen de notulen kunnen voor nu gewijzigd worden. Omdat de begunstigde en verhuring dieper gekoppeld zijn in het systeem.')
                    ->compact()
                    ->columns(12)
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Notitie')
                            ->readOnlyOn('view')
                            ->hint('Let op! Deze notulen worden meegedeeld op de factuur')
                            ->hintColor('danger')
                            ->rows(4)
                            ->columnSpan(12),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make(trans('Offerte informatie'))
                ->icon('heroicon-o-document-text')
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->description('De algemene informatie omtrent de offerte')
                ->compact()
                ->collapsible()
                ->collapsed()
                ->schema([
                    Fieldset::make(trans('Algemene informatie'))
                        ->columns(12)
                        ->schema([
                            TextEntry::make('reference')->label('Referentie')->weight(FontWeight::Bold)->color('primary')->columnSpan(2),
                            TextEntry::make('creator.name')->label('Opgesteld door')->columnSpan(3)->placeholder('-'),
                            TextEntry::make('lease.period')->label('Verhuringsperiode')->columnSpan(2),
                            TextEntry::make('status')->label('Offerte status')->badge()->columnSpan(2),
                            TextEntry::make('quotation_due_at')->label('Verval datum')->columnSpan(3)->date('d/m/Y')->placeholder('-'),
                        ]),

                    Fieldset::make(trans('Begunstigde'))
                        ->columns(12)
                        ->schema([
                            TextEntry::make('reciever.name')->label('Naam')->columnSpan(2)->icon('heroicon-o-user-circle')->iconColor('primary'),
                            TextEntry::make('reciever.address')->label('Adres')->columnSpan(4)->icon('heroicon-o-map-pin')->iconColor('primary')->placeholder('Onbekend of niet opgegeven'),
                            TextEntry::make('reciever.email')->label('Email adres')->columnSpan(3)->icon('heroicon-o-envelope')->iconColor('primary'),
                            TextEntry::make('reciever.phone_number')->label('Telefoon nummer')->columnSpan(3)->icon('heroicon-o-phone')->iconColor('primary')->placeholder('Onbekend on niet opgegeven'),

                        ]),

                    Fieldset::make(trans('Extra informatie'))
                        ->columns(12)
                        ->schema([
                            TextEntry::make('description')->label('Extra informatie')->columnSpan(12)->hiddenLabel(),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(trans('Geen offertes gevonden'))
            ->emptyStateDescription(trans('Momenteel zijn er nog geen offertes gevonden in het systeem aangemaakt of gevonden die voldoen aan de opgegeven criteria.'))
            ->columns([
                Tables\Columns\TextColumn::make('reference')->label(trans('Referentie nr.'))->weight(FontWeight::Bold)->color('primary')->searchable(),
                Tables\Columns\TextColumn::make('creator.name')->label(trans('Opgesteld door'))->placeholder('-')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('reciever.name')->label(trans('Begunstigde'))->searchable(),
                Tables\Columns\TextColumn::make('lease.period')->label(trans('Verhuringsperiode'))->color('primary')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('quotation_due_at')->label(trans('vervaldatum'))->date()->sortable()->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')->label(trans('Aangevraagd op'))->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function getRelations(): array
    {
        return [QuotationLinesRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'view' => Pages\ViewQuotations::route('/{record}'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
