<?php

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Widgets;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class DepositStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = '2';

    protected function getStats(): array
    {
        return [
            Stat::make(trans('Waarborgen in bewaring'), $this->getDepositsInCustody()),
            Stat::make(trans('Tegoeden in bewaring'), $this->getTotalDepositsInCustody()),
            Stat::make(trans('Gedeeltelijk of volledig ingetrokken'), $this->getDepositsRevokedOrPartiallyRevoked()),
            Stat::make(trans('Ingetrokken tegoeden'), '192.1k'),
        ];
    }

    private function getTotalDepositsInCustody(): string
    {
        return '€ ' . Deposit::query()->where('status', DepositStatus::Paid)->sum('paid_amount');
    }

    private function getDepositsInCustody(): int|string
    {
        return Deposit::query()->where('status', DepositStatus::Paid)->count();
    }

    private function getDepositsRevokedOrPartiallyRevoked(): int|string
    {
        return Deposit::query()
            ->where('status', DepositStatus::PartiallyRefunded)
            ->orWhere('status', DepositStatus::WithDrawn)
            ->count();
    }
}
