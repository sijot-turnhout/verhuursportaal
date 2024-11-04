<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Schemas;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Filament\Resources\LeaseResource\Pages\ViewLease;
use App\Models\Deposit;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;

final readonly class DepositInfolist
{
    public static function getLeaseInfoSection(): Section
    {
        return self::createSection(
            title: 'Koppelde verhuring',
            description: 'De informatie omtrent de verhuring die gekoppeld is aan de waarborg',
            icon: 'heroicon-o-home-modern'
        )->schema([
            TextEntry::make('lease.reference_number')
                ->label('Verhurings referentie')
                ->placeholder('-')
                ->weight(FontWeight::ExtraBold)
                ->color('primary')
                ->columnSpan(3)
                ->icon('heroicon-o-calendar-date-range')
                ->iconColor('primary')
                ->url(fn(Deposit $deposit): string => ViewLease::getUrl(['record' => $deposit->lease])),

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
                ->placeholder('- niet bekend')
                ->icon('heroicon-o-device-phone-mobile'),
        ]);
    }

    public static function getDepositInfoSection(): Section
    {
        return self::createSection(
            title: 'Waarborg informatie',
            description: 'De gegevens omtrent de waarborg betaling die een huurder heeft uitgevoerd voor zijn hruing van onze domein zijn zijn/haar kamp en weekend.',
            icon: 'heroicon-o-banknotes',
        )->schema([
            TextEntry::make('status')
                ->label('Waarborg status')
                ->badge()
                ->columnSpan(3),

            TextEntry::make('paid_amount')
                ->label('Gestorte waarborg')
                ->visible(fn (Deposit $deposit): bool => $deposit->status->is(DepositStatus::Paid))
                ->money('EUR')
                ->columnSpan(3)
                ->weight(FontWeight::ExtraBold)
                ->color('primary'),

            TextEntry::make('refunded_amount')
                ->label('Teruggestorte borg')
                ->visible(fn (Deposit $deposit): bool => $deposit->status->isNot(DepositStatus::Paid))
                ->money('EUR')
                ->columnSpan(3)
                ->weight(FontWeight::ExtraBold)
                ->color('primary'),

            TextEntry::make('paid_at')
                ->label('Betaald op')
                ->visible(fn (Deposit $deposit): bool => $deposit->status->is(DepositStatus::Paid))
                ->date()
                ->columnSpan(3),

            TextEntry::make('revoked_amount')
                ->label('Ingehouden borg')
                ->visible(fn (Deposit $deposit): bool => $deposit->status->isNot(DepositStatus::Paid))
                ->money('EUR')
                ->columnSpan(3)
                ->weight(FontWeight::ExtraBold)
                ->color('danger'),

            TextEntry::make('refunded_at')
                ->label('Teruggestort op')
                ->visible(fn (Deposit $deposit): bool => $deposit->status->isNot(DepositStatus::Paid))
                ->columnSpan(3)
                ->date(),

            TextEntry::make('refund_at')
                ->label('Uiterste terugbetalingsdatum')
                ->visible(fn (Deposit $deposit): bool => $deposit->status->is(DepositStatus::Paid))
                ->date()
                ->columnSpan(3),

            TextEntry::make('note')
                ->columnSpan(12)
                ->visible(fn (Deposit $deposit): bool => $deposit->status->notIn([DepositStatus::Paid, DepositStatus::FullyRefunded]))
                ->label('Reden tot gedeeltelijke terugbetaling of intrekking van de waarborg')
                ->color('gray')
        ]);
    }

    private static function createSection(string $title, string $icon, string $description): Section
    {
        return Section::make(trans($title))
            ->description(trans($description))
            ->icon($icon)
            ->iconColor('primary')
            ->collapsible()
            ->compact()
            ->columns(12);
    }
}
