<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Widgets;

use App\Enums\QuotationStatus;
use App\Filament\Resources\QuotationResource;
use App\Models\Quotation;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class LastestQuotationRequestsTable
 *
 * Represents a widget that displays the latest quotation requests in a table format
 * on the Filament dashboard. This widget provides an overview of new quotation requests
 * with options for viewing detailed information.
 *
 * @package App\Filament\Resources\QuotationResource\Widgets
 */
final class LastestQuotationRequestsTable extends BaseWidget
{
    /**
     * Variable to define the screen width of the widget in the dashboard
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * The name of the dashboard widget in the dashboard.
     */
    protected static ?string $heading = 'Nieuwe offerte aanvragen';

    /**
     * Configure the sorting option in the dashboard.
     */
    protected static ?int $sort = 3;

    /**
     * Determine whether the widget is visible in the dashboard or not.
     */
    public static function canView(): bool
    {
        return self::baseQueryForWidget()->count() > 0;
    }

    /**
     * Method to compose the data table for the widget.
     *
     * @param  Table $table The instance that will be used to build the table.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(self::baseQueryForWidget())
            ->paginationPageOptions([3, 6, 9, 12])
            ->extremePaginationLinks()
            ->headerActions([
                Tables\Actions\Action::make('offertes')->label('Offertes')->url(QuotationResource::getUrl())->color('gray')->icon('heroicon-o-eye'),
            ])
            ->actions([
                Tables\Actions\Action::make('Open')->url(fn(Quotation $record): string => QuotationResource::getUrl('view', ['record' => $record])),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('reference')->label('Referentie nr.')->sortable()->weight(FontWeight::Bold)->color('primary'),
                Tables\Columns\TextColumn::make('lease.period')->label('Verhuringsperiode')->sortable(),
                Tables\Columns\TextColumn::make('reciever.name')->label('Begunstigde'),
                Tables\Columns\TextColumn::make('lease.group')->label('Organisatie'),
                Tables\Columns\TextColumn::make('updated_at')->label('Laast bewerkt')->date()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->date()->label('Aangevraagd op')->sortable(),
            ]);
    }

    /**
     * The base query to fill in the results for the quotation widget.
     *
     * @return Builder<\App\Models\Invoice>
     */
    private static function baseQueryForWidget(): Builder
    {
        return QuotationResource::getEloquentQuery()->where('status', QuotationStatus::Draft);
    }
}
