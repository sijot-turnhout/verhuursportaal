<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Widgets;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

/**
 * @todo Implement class doc
 */
final class DepositStatsOverview extends BaseWidget
{
    /**
     * @todo implement docblock
     */
    protected int | string | array $columnSpan = '2';

    /**
     * Retrieves an array of statistical data related to deposits and assets.
     *
     * This method generates a comprehensive set of statistics encompassing various aspects of deposits
     * and assets held in custody, partially or fully revoked deposits, and the corresponding amounts.
     * Each statistic includes a label, value, and optional icons with colors to indicate the status.
     *
     * Returns an array of statistical data objects depicting the status and value of deposits and assets.
     *
     * @return array<mixed>
     */
    protected function getStats(): array
    {
        return [
            Stat::make(trans('Waarborgen in bewaring'), $this->getDepositsInCustody())
                ->icon('heroicon-o-shield-check')
                ->iconColor('success'),

            Stat::make(trans('Tegoeden in bewaring'), $this->getTotalAssetsInCustody())
                ->icon('heroicon-o-banknotes')
                ->iconColor('success'),

            Stat::make(trans('Gedeeltelijk of volledig ingetrokken'), $this->getDepositsRevokedOrPartiallyRevoked())
                ->icon('heroicon-o-shield-exclamation')
                ->iconColor('warning'),

            Stat::make(trans('Ingetrokken tegoeden'), $this->getTotalRevokedOrWithdrawnAssetsAmount())
                ->icon('heroicon-o-banknotes')
                ->iconColor('warning'),
        ];
    }

    /**
     * Computes the total monetary value of assets currently in custody.
     *
     * This method calculates the sum of all deposits that have a status of `Paid`. The total amount
     * represents the assets that are currently held in custody. The result is returned in a formatted
     * Euro currency string.
     *
     * @return string The formatted Euro currency string representing the total amount of assets in custody.
     */
    private function getTotalAssetsInCustody(): string
    {
        $deposits = Deposit::query()
            ->where('status', DepositStatus::Paid)
            ->orWhere('status', DepositStatus::DueRefund)
            ->sum('paid_amount');

        return '€ ' . $deposits;
    }

    /**
     * Retrieves the total number of deposits that are currently in custody.
     *
     * This method counts the deposits with a status of `Paid`, indicating that the
     * assets are being held in trust or custody and have not been refunded or withdrawn.
     *
     * @return int The number of deposits that are currently in custody.
     */
    private function getDepositsInCustody(): int
    {
        return Deposit::query()
            ->where('status', DepositStatus::Paid)
            ->orWhere('status', DepositStatus::DueRefund)
            ->count();
    }

    /**
     * Computes the total monetary value of assets that have been revoked or withdrawn.
     *
     * This method aggregates the sum of revoked amounts from deposits with statuses of `PartiallyRefunded`
     * or `WithDrawn`. These statuses indicate that either part or all of the deposit has been
     * withheld rather than refunded, resulting in monetary values needing to be accounted as revoked.
     *
     * @return string The formatted Euro currency string representing the total amount of revoked assets.
     */
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
