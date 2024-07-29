<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Infolists\InvoiceInfolist;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\Pages\ListInvoices;
use App\Filament\Resources\InvoiceResource\RelationManagers\InvoiceLinesRelationManager;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceStats;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use App\Filament\Clusters\Billing;

final class InvoiceResource extends Resource
{
    use InvoiceInfolist;

    protected static ?string $cluster = Billing::class;

    /**
     * The database model entity that will be used by the resource.
     */
    protected static ?string $model = Invoice::class;

    /**
     * The singular name of the resource class in the views.
     */
    protected static ?string $modelLabel = 'Factuur';

    /**
     * The plural resource name.
     */
    protected static ?string $pluralModelLabel = 'Facturen';

    /**
     * The name of the icon that will be displayed in the application navigation.
     */
    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    /**
     * The navigation group where the resource is placed under.
     */
    protected static ?string $navigationGroup = 'Facturatie';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Factuur informatie')
                ->icon('heroicon-o-document-text')
                ->iconColor('primary')
                ->collapsible()
                ->description('De algemene informatie van een factuur. Let wel op alleen de notulen kunnen voor nu gewijzigd worden. Omdat de begunstigde en verhuring dieper zijn gekoppeld in het systeem.')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Notitie')
                        ->readOnlyOn('view')
                        ->hint('Let op! Deze notulen worden meegedeeld op de factuur')
                        ->hintColor('danger')
                        ->rows(4)
                        ->columnSpan(12),
                ])
                ->compact()
                ->columns(12),
        ]);
    }

    public static function getWidgets(): array
    {
        return [InvoiceStats::class];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-inbox')
            ->emptyStateHeading(trans('Geen facturen gevonden'))
            ->emptyStateDescription(trans('Momenteel zijn er geen facturen gevonden met de matchende criteria.'))
            ->columns([
                Tables\Columns\TextColumn::make('payment_reference')
                    ->label('Factuurnummer')
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lease.period')->label('verhuringsperiode')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('creator.name')->label('opgesteld door')->icon('heroicon-o-user-circle')->iconColor('primary'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->label('huurder')->searchable()->icon('heroicon-o-user')->iconColor('primary'),
                Tables\Columns\TextColumn::make('invoice_total')->label('openstaand bedrag')->money('EUR'),
                Tables\Columns\TextColumn::make('due_at')->label('uiterste betalingsdatum')->date()->placeholder(new HtmlString('<i><strong>N.V.T</strong></i>')),
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

    public static function getNavigationBadge(): ?string
    {
        if ($count = Invoice::query()->excludeQuotations()->count()) {
            return (string) $count;
        }

        return null;
    }

    public static function getRelations(): array
    {
        return [InvoiceLinesRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
