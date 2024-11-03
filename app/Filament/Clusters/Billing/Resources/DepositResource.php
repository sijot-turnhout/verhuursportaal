<?php

namespace App\Filament\Clusters\Billing\Resources;

use App\Filament\Clusters\Billing;
use App\Filament\Clusters\Billing\Resources\DepositResource\Pages;
use App\Models\Deposit;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
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
        return $infolist->schema([
            Section::make('Koppelde verhuring')
                ->description('De informatie omtrent de verhuring die gekoppeld is aan de waarborg')
                ->icon('heroicon-o-home-modern')
                ->collapsible()
                ->compact()
                ->columns(12)
                ->iconColor('primary')
                ->schema([
                    TextEntry::make('lease.period')
                        ->label('Verhuringsperiode')
                        ->weight(FontWeight::ExtraBold)
                        ->color('primary')
                        ->columnSpan(3)
                        ->icon('heroicon-o-calendar-date-range')
                        ->iconColor('primary'),

                    TextEntry::make('lease.tenant.name')
                        ->label('Huurder')
                        ->columnSpan(3)
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    TextEntry::make('lease.tenant.email')
                        ->label('Email adres')
                        ->columnSpan(3)
                        ->icon('heroicon-o-envelope')
                        ->iconColor('primary'),

                    TextEntry::make('lease.tenant.phone_number')
                        ->label('Tel. nummer')
                        ->columnSpan(3)
                        ->iconColor('primary')
                        ->icon('heroicon-o-device-phone-mobile'),
                ]),

            Section::make('Waarborg informatie')
                ->description('De gegevens omtrent de waarborg betaling die een huurder heeft uitgevoerd voor zijn hruing van onze domein zijn zijn/haar kamp en weekend.')
                ->icon('heroicon-o-banknotes')
                ->iconColor('primary')
                ->compact()
                ->columns(12)
                ->schema([
                    TextEntry::make('status')->label('Waarborg status')->badge()->columnSpan(3),
                    TextEntry::make('amount')->label('Gestorte waarborg')->money('EUR')->columnSpan(3)->weight(FontWeight::ExtraBold)->color('primary'),
                    TextEntry::make('paid_at')->label('Betaald op')->date()->columnSpan(3),
                    TextEntry::make('refund_at')->label('Uiterste terugbetalingsdatum')->date()->columnSpan(3),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Geen geregistreerde waarborgen')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateDescription('Het lijkt erop dat er voor de moment geen geregistreerde waarborgen zijn die voldoen aan de opgegeven criteria')
            ->columns([
                TextColumn::make('lease.reference_number')
                    ->label('Verhurings referentie')
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
                    ->label('Waarborg')
                    ->badge()
                    ->translateLabel()
                    ->sortable(),

                TextColumn::make('amount')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeposits::route('/'),
            'view' => Pages\ViewDeposit::route('/{record}'),
        ];
    }
}
