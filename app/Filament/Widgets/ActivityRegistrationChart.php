<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use Carbon\Carbon;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;

final class ActivityRegistrationChart extends AdvancedChartWidget
{
    protected static ?string $icon = 'heroicon-o-pencil-square';
    protected static ?string $iconColor = 'danger';
    protected static string $color = 'danger';
    protected static ?string $iconBackgroundColor = 'danger';
    protected static ?string $badgeIconPosition = 'after';
    protected static ?string $maxHeight = '150px';
    protected int|string|array $columnSpan = 'full';

    protected static ?array $options = [
        'scales' => [
            'y' => ['stacked' => true, 'display' => true, 'beginAtZero' => true, 'ticks' => ['stepSize' => 1]],
            'x' => ['stacked' => true],
        ],
        'plugins' => ['legend' => ['display' => true, 'fill' => true]],
    ];

    public ?string $filter = 'today';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this->filter) {
            'today' => trans('Aantal geregistreerde handelingen vandaag'),
            'week' => trans('Aantal geregistreerde handelingen afgelopen week'),
            'month' => trans('Aantal geregistreerde handelingen afgelopen maand'),
            'year' => trans('Aantal geregistreerde handelingen afgelopen jaar'),
        };
    }

    public function getHeading(): string|Htmlable|null
    {
        $registerActivitiesToday = Activity::query()->whereDate('created_at', Carbon::today())->count();
        $registerActivitiesLastWeek = Activity::query()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $registerActivitiesLastMonth = Activity::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $registerActivitiesLastYear = Activity::query()->whereBetween('created_at', [now()->startOfYear(), now()->endOfyear()])->count();

        return match ($this->filter) {
            'today' => trans(':amount handelingen', ['amount' => $registerActivitiesToday]),
            'week' => trans(':amount handelingen', ['amount' => $registerActivitiesLastWeek]),
            'month' => trans(':amount handelingen', ['amount' => $registerActivitiesLastMonth]),
            'year' =>  trans(':amount handelingen', ['amount' => $registerActivitiesLastYear]),
        };
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Vandaag',
            'week' => 'Afgelopen week',
            'month' => 'Afgelopen maand',
            'year' => 'Afgelopen jaar',
        ];
    }

    protected function getData(): array
    {
        return match ($this->filter) {
            'today' => $this->getChartForToday(),
            'week' => $this->getChartForLastWeek(),
            'month' => $this->getChartForLastMonth(),
            'year' => $this->getChartForLastYear()
        };
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Retrieves activity chart data for a specified period.
     * This function leverages the `Flowframe\Trend` library to aggregate activity data and format it for chart display.
     * It supports various aggregation periods like hourly, daily, and monthly.
     *
     * @param Carbon $start The starting date of the period for which to retrieve data.
     * @param Carbon $end    The ending date of the period.
     * @param string $period The aggregation period.  Valid values are Trend constants such as 'perHour', 'perDay', 'perWeek', 'perMonth', 'perQuarter', 'perYear'.
     *                       See the Flowframe\Trend documentation for a full list of supported periods: [link to documentation if available].
     *                       Incorrect values will likely result in unexpected behavior.
     *
     * @return array An associative array containing the chart data. The array has two keys:
     *               - `datasets`: An array of datasets, each containing a label and data points.
     *               - `labels`: An array of labels for the x-axis of the chart.  The format depends on the $period parameter.
     *
     * @throws \Exception If an invalid $period value is provided. While not explicitly checked here, invalid periods may cause exceptions within the Trend library.
     *
     * @example
     *
     * $this->getActivityChartData(now()->startOfDay(), now()->endOfDay(), 'perHour');      // Get hourly data for today.
     * $this->getActivityChartData(now()->startOfMonth(), now()->endOfMonth(), 'perDay');   // Get daily data for this month.
     */
    private function getActivityChartData(Carbon $start, Carbon $end, string $period): array
    {
        $data = Trend::model(Activity::class)
            ->between(start: $start, end: $end)
            ->{$period}()
            ->count();

        return [
            'datasets' => [['label' => trans('geregistreerde handelingen'), 'data' => $data->map(fn(TrendValue $value) => $value->aggregate)]],
            'labels' => $data->map(fn(TrendValue $value) => match ($period) {
                'perHour' => Carbon::parse($value->date)->format('H:i'),
                default => $value->date,
            }),
        ];
    }

    private function getChartForToday(): array
    {
        return $this->getActivityChartData(now()->startOfDay(), now()->endOfDay(), 'perHour');
    }

    /**
     * Generate chart data for the current week.
     *
     * @return array  The activity chart data for the current week
     */
    private function getChartForLastWeek(): array
    {
        return $this->getActivityChartData(now()->startOfWeek(), now()->endOfWeek(), 'perDay');
    }

    /**
     * Retrieves activity chart data for the last month, aggregated by day.
     *
     * @return array The chart data, as returned by getActivityChartData().
     */
    private function getChartForLastMonth(): array
    {
        return $this->getActivityChartData(now()->startOfMonth(), now()->endOfMonth(), 'perDay');
    }

    /**
     * Retrieves activity chart data for the last year, aggregated by month.
     *
     * @return array The chart data, as returned by getActivityChartData().
     */
    private function getChartForLastYear(): array
    {
        return $this->getActivityChartData(now()->startOfYear(), now()->endOfYear(), 'perMonth');
    }
}
