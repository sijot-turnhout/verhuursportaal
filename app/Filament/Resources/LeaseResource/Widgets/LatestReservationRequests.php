<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Widgets;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource;
use App\Models\Lease;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\CursorPaginator;

/**
 * Class LatestReservationRequests
 *
 * This class defines a widget that displays the latest reservation requests in a table format within the Filament admin panel.
 * The widget shows recent lease requests with details such as period, responsible person, number of people, tenant, organization,
 * and request date. It provides options for pagination and navigation to the lease resource.
 *
 * @package App\Filament\Resources\LeaseResource\Widgets
 */
final class LatestReservationRequests extends BaseWidget
{
    /**
     * Defines the column span of the widget in the layout.
     *
     * @var int|string|array<string, int|null>
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * The heading text displayed at the top of the widget.
     *
     * @var string|null
     */
    protected static ?string $heading = 'Nieuwe aanvragen';

    /**
     * The sort order of the widget relative to other widgets.
     *
     * @var int|null
     */
    protected static ?int $sort = 2;

    /**
     * Configures and returns the table instance for the widget.
     *
     * @param  Table $table  The table builder instance to configure.
     * @return Table         The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-queue-list')
            ->emptyStateHeading('Geen aanvragen gevonden')
            ->emptyStateDescription('Momenteel zijn er geen nieuwe verhuringen aangevraagd door personen in het systeem')
            ->extremePaginationLinks()
            ->paginated([3, 6, 9, 12])
            ->query(LeaseResource::getEloquentQuery()->where('status', LeaseStatus::Request))
            ->headerActions([
                Tables\Actions\Action::make('verhuringen')
                    ->visible(fn(Lease $lease): bool => $lease->where('status', LeaseStatus::Request)->count() > 0)
                    ->color('gray')
                    ->icon('heroicon-o-eye')
                    ->url(LeaseResource::getUrl('index')),
            ])
            ->defaultSort('arrival_date', 'ASC')
            ->columns([
                Tables\Columns\TextColumn::make('period')->label('Periode')->weight(FontWeight::Bold)->color('primary'),
                Tables\Columns\TextColumn::make('supervisor.name')->label('Verantwoordelijke')->sortable()->placeholder('- geen toewijzing'),
                Tables\Columns\TextColumn::make('persons')->label('Aantal personen')->sortable()->badge()->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('tenant.fullName')->label('Huurder')->sortable(),
                Tables\Columns\TextColumn::make('group')->label('Organisatie')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aanvragingsdatum')->date()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')->url(fn(Lease $record): string => LeaseResource::getUrl('edit', ['record' => $record])),
            ]);
    }

    /**
     * Custom pagination logic for the table.
     *
     * @param  Builder<\App\Models\Lease>    $query   The query builder instance for the table.
     * @return Paginator<\App\Models\Lease>           The paginator instance for the query.
     */
    protected function paginateTableQuery(Builder $query): Paginator|CursorPaginator
    {
        /** @phpstan-ignore-next-line */
        return $query->simplePaginate(('all' === $this->getTableRecordsPerPage()) ? $query->count() : $this->getTableRecordsPerPage());
    }
}
