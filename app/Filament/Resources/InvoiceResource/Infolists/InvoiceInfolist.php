<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Infolists;

use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;

/**
 * Trait InvoiceInfolist
 *
 * Provides functionality to render an infolist specifically designed to display invoice details
 * in a Filament resource. This trait includes a method to configure and render an infolist with
 * various sections and fields related to invoice information.
 *
 *  @package App\Filament\Resources\InvoiceResource\Infolists
 */
trait InvoiceInfolist
{
    /**
     * The title of the infolist section. If not set, defaults to 'Factuur informatie'.
     *
     * @var string|null
     */
    protected static ?string $infolistSectionTitle = null;

    /**
     * The description of the infolist section. If not set, defaults to 'De algemene informatie omtrent de factuur.'
     *
     * @var string|null
     */
    protected static ?string $infolistSectionDescription = null;

    /**
     * Configures and renders the infolist schema for displaying invoice details.
     *
     * This method sets up the layout and content of the infolist, including various sections and fields
     * to present invoice-related information such as payment references, creator details, lease periods,
     * invoice totals, statuses, and due dates. It also includes customer details and any extra information
     * related to the invoice.
     *
     * @param  Infolist $infolist  The infolist instance that is being configured.
     * @return Infolist            The configured infolist instance with the defined schema.
     */
    public static function renderInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(trans(self::$infolistSectionTitle ?? 'Factuur informatie'))
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->iconSize(IconSize::Medium)
                    ->description(trans(self::$infolistSectionDescription ?? 'De algemene informatie omtrent de factuur.'))
                    ->compact()
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Fieldset::make('algemene informatie')
                            ->schema([
                                TextEntry::make('payment_reference')->label('Referentie')->weight(FontWeight::Bold)->color('primary')->columnSpan(2),
                                TextEntry::make('creator.name')->label('Opgesteld door')->columnSpan(2),
                                TextEntry::make('lease.period')->label('Verhuringsperiode')->columnSpan(2),
                                TextEntry::make('invoice_total')->label('Gefactureerd bedrag')->money('EUR')->columnSpan(2),
                                TextEntry::make('status')->label('Factuur status')->badge()->columnSpan(2),
                                TextEntry::make('due_at')->label('Uiterste betaaldatum')->columnSpan(2)->date('d/m/Y')->placeholder('-'),
                            ])->columns(12),

                        Fieldset::make('Begunstigde')
                            ->schema([
                                TextEntry::make('customer.name')->label('Naam')->columnSpan(2)->icon('heroicon-o-user-circle')->iconColor('primary'),
                                TextEntry::make('customer.address')->label('Adres')->columnSpan(4)->icon('heroicon-o-map-pin')->iconColor('primary'),
                                TextEntry::make('customer.email')->label('Email adres')->columnSpan(3)->icon('heroicon-o-envelope')->iconColor('primary'),
                                TextEntry::make('customer.phone_number')->label('Telefoon nummer')->columnSpan(3)->icon('heroicon-o-phone')->iconColor('primary')->placeholder('Onbekend of niet opgegeven'),
                            ])->columns(12),

                        Fieldset::make('extra informatie')->schema([
                            TextEntry::make('description')->label('Extra informatie')->columnSpan(12)->hiddenLabel(),
                        ])->columns(12),
                    ]),
            ]);
    }
}
