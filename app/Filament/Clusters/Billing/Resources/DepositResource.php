<?php

namespace App\Filament\Clusters\Billing\Resources;

use App\Filament\Clusters\Billing;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Filament\Clusters\Billing\Resources\DepositResource\Pages;
use App\Filament\Clusters\Billing\Resources\DepositResource\Schemas\DepositInfolist;
use App\Filament\Clusters\Billing\Resources\DepositResource\Widgets\DepositStatsOverview;
use App\Models\Deposit;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $modelLabel = 'waarborg';
    protected static ?string $pluralModelLabel = 'Waarborgen';
    protected static ?string $cluster = Billing::class;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(
            components: [DepositInfolist::getLeaseInfoSection(), DepositInfolist::getDepositInfoSection()]
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Geen geregistreerde waarborgen')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateDescription('Het lijkt erop dat er voor de moment geen geregistreerde waarborgen zijn die voldoen aan de opgegeven criteria')
            ->columns([
                TextColumn::make('lease.reference_number')
                    ->label('Verhuring')
                    ->placeholder('-')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-home-modern')
                    ->color('primary')
                    ->weight(FontWeight::SemiBold),

                TextColumn::make('lease.tenant.name')
                    ->label('Betaald door')
                    ->sortable()
                    ->translateLabel()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->translateLabel()
                    ->sortable(),

                TextColumn::make('paid_amount')
                    ->label('Borgsom')
                    ->translateLabel()
                    ->money('EUR'),

                TextColumn::make('paid_at')
                    ->label('Betaald op')
                    ->sortable()
                    ->translateLabel()
                    ->date(),

                TextColumn::make('refund_at')
                    ->label('Terugbetalingsdatum')
                    ->sortable()
                    ->searchable()
                    ->date(),
            ])
            ->actions([Tables\Actions\ViewAction::make()]);
    }

    public static function getNavigationBadge(): ?string
    {
        if ($count = Deposit::query()->where('status', DepositStatus::Paid)->count()) {
            return (string) $count;
        }

        return null;
    }

    public static function getWidgets(): array
    {
        return [DepositStatsOverview::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeposits::route('/'),
            'view' => Pages\ViewDeposit::route('/{record}'),
        ];
    }
}
