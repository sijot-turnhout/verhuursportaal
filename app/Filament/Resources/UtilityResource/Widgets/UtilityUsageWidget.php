<?php

declare(strict_types=1);

namespace App\Filament\Resources\UtilityResource\Widgets;

use App\DataObjects\GraphDatasetObject;
use App\Enums\UtilityMetricTypes;
use App\Filament\Support\LineChartBase;
use App\Models\Utility;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

/**
 * Class UtilityUsageWidget
 *
 * This widget class present the visual presentation of the the graph that displays us the insights of monthly utility usages
 * that are related to leases that are registered in the reservation application.
 */
final class UtilityUsageWidget extends LineChartBase
{
    /**
     * The variablem that contains the applied filter from the chart widget.
     *
     * @var string|null
     */
    public ?string $filter = 'unit';

    /**
     * Method for registering the Heading title of the chart panel in the widget.
     *
     * @return string
     */
    public function getHeading(): string
    {
        return match ($this->filter) {
            'unit' => trans('Nutsverbuik overzicht'),
            'price' => trans('Prijs van het nutsverbruik'),
            default => trans('Nutsverbuik overzicht'),
        };
    }

    /**
     * Method for registering the chart panel description text.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return trans('Statistische weergave van het nutsverbuik tijdens verhuringen die geregistreerd staan in :app', ['app' => config('app.name', 'Laravel')]);
    }

    /**
     * The function that compile all the needed datasets and prepare all the necessarly data for rendering in the chart view.
     *
     * @return array<mixed, mixed>
     */
    protected function getData(): array
    {
        $utilityUsageStatistics = match ($this->filter) {
            'unit' => $this->getUsageInformation(),
            'price' => $this->getUsageInformation('billing_amount'),
            default => $this->getUsageInformation(),
        };

        return [
            'datasets' => $utilityUsageStatistics,
            'labels' => $this->getChartBarInformation(UtilityMetricTypes::Gas, 'usage_total')->map(fn(TrendValue $value) => $value->date),
        ];
    }

    /**
     * @return array|bool[]|float[]|int[]|string[]
     */
    protected function getFilters(): array
    {
        return [
            'unit' => trans('Eenheden'),
            'price' => trans('Prijs'),
        ];
    }

    /**
     * Method for getting a chart dataset out of the database.
     *
     * @param  UtilityMetricTypes  $usageMetricTypes  The enum class that contains all the utility metric categories
     * @return Collection<int|string, TrendValue>
     */
    protected function getChartBarInformation(UtilityMetricTypes $usageMetricTypes, string $sumColumn): Collection
    {
        return Trend::query(Utility::query()->where('name', $usageMetricTypes))
            ->between(start: now()->startOfYear(), end: now()->endOfYear())
            ->perMonth()
            ->sum($sumColumn);
    }

    /**
     * The chart type to display in the component.
     */
    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * Method for getting the chart information about the utility usages.
     *
     * @param  string $sumColumn  The database column from where we want to calculate the totals
     * @return array<mixed>
     */
    private function getUsageInformation(string $sumColumn = 'usage_total'): array
    {
        $gasUsageBar = $this->getChartBarInformation(UtilityMetricTypes::Gas, $sumColumn);
        $waterUsageBar = $this->getChartBarInformation(UtilityMetricTypes::Water, $sumColumn);
        $electricityUsageBar = $this->getChartBarInformation(UtilityMetricTypes::Electricity, $sumColumn);

        return [
            (new GraphDatasetObject('Gas verbruik', $gasUsageBar->map(fn(TrendValue $value) => $value->aggregate), '#ca8a04', '#ca8a04', '#ca8a04'))->toArray(),
            (new GraphDatasetObject('Water verbruik', $waterUsageBar->map(fn(TrendValue $value) => $value->aggregate), '#1d4ed8', '#1d4ed8', '#1d4ed8'))->toArray(),
            (new GraphDatasetObject('Elektriciteits verbruik', $electricityUsageBar->map(fn(TrendValue $value) => $value->aggregate), '#6d28d9', '#6d28d9', '#6d28d9'))->toArray(),
        ];
    }
}
