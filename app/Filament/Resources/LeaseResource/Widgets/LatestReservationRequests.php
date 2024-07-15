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

final class LatestReservationRequests extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Nieuwe aanvragen';
    protected static ?int $sort = 2;

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
                    ->visible(fn (Lease $lease): bool => $lease->where('status', LeaseStatus::Request)->count() > 0)
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

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate(('all' === $this->getTableRecordsPerPage()) ? $query->count() : $this->getTableRecordsPerPage());
    }
}
