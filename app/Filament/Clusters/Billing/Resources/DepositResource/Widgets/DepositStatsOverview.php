<?php

declare(strict_types=1);

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
            Stat::make(trans('Tegoeden in bewaring'), $this->getTotalAssetsInCustody()),
            Stat::make(trans('Gedeeltelijk of volledig ingetrokken'), $this->getDepositsRevokedOrPartiallyRevoked()),
            Stat::make(trans('Ingetrokken tegoeden'), $this->getTotalRevokedOrWithdrawnAssetsAmount()),
        ];
    }

    private function getTotalAssetsInCustody(): string
    {
        return '€ ' . Deposit::query()->where('status', DepositStatus::Paid)->sum('paid_amount');
    }

    private function getDepositsInCustody(): int
    {
        return Deposit::query()->where('status', DepositStatus::Paid)->count();
    }

    private function getTotalRevokedOrWithdrawnAssetsAmount(): string
    {
        $revokedAssets = Deposit::query()
            ->where('status', DepositStatus::PartiallyRefunded)
            ->orWhere('status', DepositStatus::WithDrawn)
            ->sum('revoked_amount');

        return '€ ' . $revokedAssets;
    }

    /**
     * Computes the total monetary value of assets that have been revoked or withdrawn.
     *
     * This method calculates the total amount from deposits with statuses of `PartiallyRefunded`
     * or `WithDrawn`. These statuses indicate that either part or all of the deposit has been
     * withheld rather than refunded, resulting in revoked assets.
     *
     * @return int The formatted Euro currency string representing the total amount of revoked assets.
     */
    private function getDepositsRevokedOrPartiallyRevoked(): int
    {
        return Deposit::query()
            ->where('status', DepositStatus::PartiallyRefunded)
            ->orWhere('status', DepositStatus::WithDrawn)
            ->count();
    }
}
