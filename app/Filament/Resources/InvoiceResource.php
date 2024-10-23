<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\Billing;
use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Filament\Resources\InvoiceResource\Infolists\InvoiceInfolist;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\Pages\ListInvoices;
use App\Filament\Resources\InvoiceResource\RelationManagers\InvoiceLinesRelationManager;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceStats;
use App\Models\Invoice;
use App\Models\Lease;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

/**
 * Class InvoiceResource
 *
 * This resource handles the management of the `Invoice` model, providing forms, tables, and views for creating, editing, and listing invoices.
 * The class also integrates widgets and relation managers to offer a comprehensive interface for working with invoices.
 * Invoices can be filtered, displayed with specific data, and various actions can be performed on them, such as viewing, editing, and deleting.
 *
 * @package App\Filament\Resources
 */
final class InvoiceResource extends Resource
{
    use InvoiceInfolist;

    /**
     * Specifies the cluster to which this resource belongs.
     * In this case, the resource is grouped under the "Billing" cluster in the application.
     *
     * @var string|null
     */
    protected static ?string $cluster = Billing::class;

    /**
     * The database model used by this resource.
     *
     * @var string|null
     */
    protected static ?string $model = Invoice::class;

    /**
     * The singular label for the resource used in views.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Factuur';

    /**
     * The plural label for the resource, shown in navigation and table views.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Facturen';

    /**
     * The navigation icon for the resource.
     * This icon is shown in the sidebar navigation.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    /**
     * Defines the form schema for creating or editing invoices.
     * In this case, the form only allows for editing the `description` (notes) of the invoice.
     *
     * @param  Form $form The instance that will be used to build the form in the resource view.
     * @return Form
     */
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

    /**
     * Defines the widgets used on the invoice overview page, such as the `InvoiceStats` widget.
     *
     * @return array
     */
    public static function getWidgets(): array
    {
        return [InvoiceStats::class];
    }

    /**
     * Defines the infolist used on the invoice information displays.
     *
     * @param  Infolist $infolist The instance that will be usezd to build the infolist view.
     * @return Infolist
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informatie omtrent de begunstigde')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed()
                    ->compact()
                    ->columns(12)
                    ->description('De algemene informatie omtrent de begunstigde van de factuur.')
                    ->schema([
                        TextEntry::make('customer.name')->label('Naam')->icon('heroicon-o-user')->columnSpan(3)->iconColor('primary'),
                        TextEntry::make('customer.email')->label('Email')->columnSpan(3)->icon('heroicon-o-envelope')->iconColor('primary'),
                        TextEntry::make('customer.phone_number')->label('Telefoon nummer')->icon('heroicon-o-device-phone-mobile')->iconColor('primary')->iconColor('primary')->columnSpan(3)->placeholder('- niet opgegegeven'),
                        TextEntry::make('customer.address')->label('Adres')->icon('heroicon-o-home-modern')->iconColor('primary')->placeholder('(niet opgegeven)')->columnSpan(3),
                    ]),

                // Creates a collapsible section for displaying general invoice information.
                //
                // The section includes details such as the invoice number, status, due date, and other relevant details.
                // Each field is displayed as a text entry with additional formatting like icons, badges, and color schemes.
                // Some fields have dynamic visibility based on the invoice status. The section also provides a link to view the related lease information.

                Section::make('Algemene informatie')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed()
                    ->compact()
                    ->columns(12)
                    ->description('De factuur informatie weergave toont een overzicht van factuurnummer, datum, status en totaalbedrag, inclusief factuurregels en betalingsdetails')
                    ->schema([
                        TextEntry::make('payment_reference')->label('Volgnummer')->weight(FontWeight::Bold)->color('primary')->columnSpan(2),
                        TextEntry::make('status')->badge()->columnSpan(2),
                        TextEntry::make('lease.period')
                            ->columnSpan(5)
                            ->url(fn (Invoice $invoice): string => LeaseResource::getUrl('view', ['record' => $invoice->lease]))
                            ->openUrlInNewTab(),

                        TextEntry::make('due_at')
                            ->label('Uiterste betalingsdatum')
                            ->columnSpan(3)
                            ->icon('heroicon-o-calendar')
                            ->iconColor('primary')
                            ->placeholder('-')
                            ->visible(fn (Invoice $invoice): bool => $invoice->status === InvoiceStatus::Open)->date('d-m-Y'),

                        TextEntry::make('cancelled_at')
                            ->label('Annuleringsdatum')
                            ->columnSpan(3)
                            ->icon('heroicon-o-calendar')
                            ->iconColor('primary')
                            ->placeholder('-')
                            ->visible(fn (Invoice $invoice): bool => $invoice->status === InvoiceStatus::Void || $invoice->status === InvoiceStatus::Uncollected),

                            TextEntry::make('paid_at')
                                ->label('Betaald op')
                                ->columnSpan(3)
                                ->icon('heroicon-o-calendar')
                                ->iconColor('primary')
                                ->placeholder('-')
                                ->visible(fn (Invoice $invoice): bool => $invoice->status === InvoiceStatus::Paid),

                        TextEntry::make('description')->label('Extra informatie')->columnSpan(12),
                    ]),
            ]);
    }

    /**
     * Defines the table schema for listing invoices.
     *
     * The table includes various columns, such as the payment reference, lease period, creator, customer, and due date.
     * The table supports searchable and sortable columns, as well as specific actions like viewing, editing, and deleting invoices.
     *
     * @param  Table $table The instance that will be used to render the information table on the overview page.
     * @return Table
     */
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

    /**
     * Returns the number of active invoices as a navigation badge.
     * This badge will display the number of invoices that are not quotations.
     *
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        if ($count = Invoice::query()->count()) {
            return (string) $count;
        }

        return null;
    }

    /**
     * Defines the relation managers for the resource.
     * In this case, it includes `InvoiceLinesRelationManager` which manages invoice lines associated with each invoice.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [InvoiceLinesRelationManager::class];
    }

    /**
     * Defines the resource's pages and their respective routes.
     * Includes pages for listing, creating, viewing, and editing invoices.
     *
     * @return array
     */
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
