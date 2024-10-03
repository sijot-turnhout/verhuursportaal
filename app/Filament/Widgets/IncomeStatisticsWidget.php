<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\UserGroup;
use App\Enums\UtilityMetricTypes;
use App\Features\UtilityMetrics;
use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\BillingItem;
use App\Models\Utility;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Pennant\Feature;
use NumberFormatter;

/**
 * Class IncomeStatisticsWidget
 *
 * A widget to display various income and utility statistics in the Filament admin panel.
 *
 * @package App\Filament\Widgets
 */
final class IncomeStatisticsWidget extends BaseWidget
{
    /**
     * Formatter for displaying the currency values.
     *
     * @var NumberFormatter $currencyFormatter
     */
    private NumberFormatter $currencyFormatter;

    /**
     * IncomeStatisticsWidget constructor
     *
     * Initializes the NumberFormatter for currency display.
     */
    public function __construct()
    {
        $this->currencyFormatter = new NumberFormatter('nl_BE', NumberFormatter::CURRENCY);
    }

    /**
     * Determine whether the revenue statistics can be displayed or not.
     *
     * @return bool
     */
    public static function canView(): bool
    {
        return (UserGroup::Rvb === auth()->user()->user_group || UserGroup::Webmaster === auth()->user()->user_group)
            && Feature::activate(UtilityMetrics::class);
    }

    /**
     * Retrieve the statistics to be displayed in the widget.
     *
     * @return array Array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            $this->calculateRevenue(),
            $this->calculateUtilityUsage(UtilityMetricTypes::Gas),
            $this->calculateUtilityUsage(UtilityMetricTypes::Water),
            $this->calculateUtilityUsage(UtilityMetricTypes::Electricity),
        ];
    }

    /**
     * Calculate the total revenue and discounts, and return the result as a Stat object.
     *
     * @return Stat The Stat object representing total revenue minus discounts.
     */
    private function calculateRevenue(): Stat
    {
        $revenue = $this->getBillingItemSum(BillingType::BillingLine);
        $discounts = $this->getTotalDiscounts();

        $total = $revenue - $discounts;

        return Stat::make('Totale winst', $this->formatCurrency((float) $total))
            ->description($this->formatCurrency((float) $discounts) . ' onkosten')
            ->icon('heroicon-o-arrow-trending-up')
            ->descriptionIcon('heroicon-o-arrow-trending-down', IconPosition::Before)
            ->descriptionColor('danger');
    }

    /**
     * Calculate the total discounts from both billing items and utility discounts.
     *
     * @return float The total amount of discounts.
     */
    private function getTotalDiscounts(): float
    {
        $billingDiscounts = $this->getBillingItemSum(BillingType::Discount);
        $utilityDiscounts = $this->getUtilityDiscounts();

        return $billingDiscounts + $utilityDiscounts;
    }

    /**
     * Calculate the total utility discounts for electricity, water, and gas.
     *
     * @return float The total amount of utility discounts.
     */
    private function getUtilityDiscounts(): float
    {
        return $this->getUtilitySum(UtilityMetricTypes::Electricity, 'billing_amount') +
            $this->getUtilitySum(UtilityMetricTypes::Water, 'billing_amount') +
            $this->getUtilitySum(UtilityMetricTypes::Gas, 'billing_amount');
    }

    /**
     * Calculate the utility usage and billing amount for a given utility type and return it as a Stat object.
     *
     * @param  UtilityMetricTypes  $metricType  The type of utility metric (Gas, Water, Electricity).
     * @return Stat                             The Stat object representing the utility usage and billing amount.
     */
    private function calculateUtilityUsage(UtilityMetricTypes $metricType): Stat
    {
        $usageTotal = $this->getUtilitySum($metricType, 'usage_total');
        $billingAmount = $this->getUtilitySum($metricType, 'billing_amount');

        $statTitle = sprintf('%s (%s)', $metricType->value, $metricType->getSuffix());

        return Stat::make($statTitle, $this->formatNumber($usageTotal, 3))
            ->icon($metricType->getIcon())
            ->description('Verbruikskost: ' . $this->formatCurrency($billingAmount))
            ->descriptionIcon('heroicon-o-currency-euro', IconPosition::Before)
            ->descriptionColor('danger');
    }

    /**
     * Format a number to a specified number of decimal places and using a comma as the decimal separator.
     *
     * @param  float $number    The number to format.
     * @param  int   $decimals  The number of decimal places.
     * @return string           The formatted number.
     */

    private function formatNumber(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Format a currency amount according to the locale settings.
     *
     * @param  float  $amount. The amount to format.
     * @return string|bool     The formatted currency amount or false on failure.
     */
    private function formatCurrency(float $amount): string|bool
    {
        return $this->currencyFormatter->formatCurrency($amount, 'EUR');
    }


    /**
     * Calculate the sum of a specified column for a given utility type.
     *
     * @param  UtilityMetricTypes  $metricType  The type of utility metric (Gas, Water, Electricity).
     * @param  string              $column      The column to sum.
     * @return float                            The total sum of the specified column.
     */
    private function getUtilitySum(UtilityMetricTypes $metricType, string $column): float
    {
        return (float) Utility::query()
            ->where('name', $metricType)
            ->sum($column);
    }

    /**
     * Calculate the sum of the total price for billing items of a specified type, excluding certain invoice statuses.
     *
     * @param  BillingType  $billingLine  The type of billing item (BillingLine, Discount).
     * @return float|string               The total sum of the billing items.
     */
    private function getBillingItemSum(BillingType $billingLine): float|string
    {
        return BillingItem::query()
            ->where('type', $billingLine)
            ->whereHas('invoice', function (Builder $builder): void {
                $builder->whereNotIn('status', [
                    InvoiceStatus::Quotation_Request,
                    InvoiceStatus::Quotation,
                    InvoiceStatus::Void,
                    InvoiceStatus::Quotation_Declined,
                ]);
            })
            ->sum('total_price');
    }
}
