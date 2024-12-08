<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Activity;
use Carbon\Carbon;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Exception;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;

final class ActivityRegistrationChart extends AdvancedChartWidget
{
    /**
     * The currently applied filter for the chart data. Defaults to 'today'.
     * Available options are typically 'today', 'week', 'month', and 'year'.
     *
     * @var string|null
     */
    public ?string $filter = 'today';

    /**
     * The icon to display for the widget.
     * Uses Heroicons as icon library.
     *
     * @var string|null
     */
    protected static ?string $icon = 'heroicon-o-pencil-square';

    /**
     * The color of the widget's icon. Corresponds to Tailwind CSS color names.
     *
     * @var string|null
     */
    protected static ?string $iconColor = 'danger';

    /**
     * The primary color of the widget. Corresponds to Tailwind CSS color names.
     *
     * @var string
     */
    protected static string $color = 'danger';

    /**
     * The background color of the widget's icon. Corresponds to Tailwind CSS color names.
     *
     * @var string|null
     */
    protected static ?string $iconBackgroundColor = 'danger';

    /**
     * The position of the badge icon relative to the badge text. Can be 'before' or 'after'.
     *
     * @var string|null
     */
    protected static ?string $badgeIconPosition = 'after';

    /**
     * The maximum height of the widget. Uses Tailwind CSS units.
     *
     * @var string|null
     */
    protected static ?string $maxHeight = '150px';

    /**
     * The column span of the widget. Can be 'full', an integer, or an array for responsive behaviour.
     *
     * {@inheritDoc}
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * Configuration options for the chart.
     * This controls things like the axes, legend, and stacking behaviour.
     *
     * 'scales' configures the x and y axes.
     * 'y' is set to stack data, always display, start at zero, and have ticks increment by 1.
     * 'x' is also set to stack data.
     * 'plugins' configures the chart's legend to be displayed and filled.
     *
     * {@inheritDoc}
     */
    protected static ?array $options = [
        'scales' => [
            'y' => ['stacked' => true, 'display' => true, 'beginAtZero' => true, 'ticks' => ['stepSize' => 1]],
            'x' => ['stacked' => true],
        ],
        'plugins' => ['legend' => ['display' => true, 'fill' => true]],
    ];

    /**
     * Gets the label (title) for the chart based on the selected filter.
     *
     * @return string|Htmlable|null The translated chart label.
     */
    public function getLabel(): string|Htmlable|null
    {
        /**
         * Ignore following PHPStan error here (Match expression does not handle remaining values: string|null)
         * Becasue there is a default variable assigned ino the static class variables.
         *
         * @phpstan-ignore-next-line
         */
        return match ($this->filter) {
            'today' => trans('Aantal geregistreerde handelingen vandaag'),
            'week' => trans('Aantal geregistreerde handelingen afgelopen week'),
            'month' => trans('Aantal geregistreerde handelingen afgelopen maand'),
            'year' => trans('Aantal geregistreerde handelingen afgelopen jaar'),
        };
    }

    /**
     * Gets the chart's heading, which displays the total number of registered activities based on the selected filter.
     *
     * @return string|Htmlable|null The translated heading with the activity count.
     */
    public function getHeading(): string|Htmlable|null
    {
        $registerActivitiesToday = Activity::query()->whereDate('created_at', Carbon::today())->count();
        $registerActivitiesLastWeek = Activity::query()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $registerActivitiesLastMonth = Activity::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $registerActivitiesLastYear = Activity::query()->whereBetween('created_at', [now()->startOfYear(), now()->endOfyear()])->count();

        /**
         * Ignore following PHPStan error here (Match expression does not handle remaining values: string|null)
         * Becasue there is a default variable assigned ino the static class variables.
         *
         * @phpstan-ignore-next-line
         */
        return match ($this->filter) {
            'today' => trans(':amount handelingen', ['amount' => $registerActivitiesToday]),
            'week' => trans(':amount handelingen', ['amount' => $registerActivitiesLastWeek]),
            'month' => trans(':amount handelingen', ['amount' => $registerActivitiesLastMonth]),
            'year' =>  trans(':amount handelingen', ['amount' => $registerActivitiesLastYear]),
        };
    }

    /**
     * Returns the available filters for the chart.
     *
     * @return array<string, string>|null The available filter options.
     */
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Vandaag',
            'week' => 'Afgelopen week',
            'month' => 'Afgelopen maand',
            'year' => 'Afgelopen jaar',
        ];
    }

    /**
     * Retrieves the data to be displayed in the chart.  The data returned depends on the currently selected filter.
     *
     * @return array<mixed> The chart data.
     */
    protected function getData(): array
    {
        /**
         * Ignore following PHPStan error here (Match expression does not handle remaining values: string|null)
         * Becasue there is a default variable assigned ino the static class variables.
         *
         * @phpstan-ignore-next-line
         */
        return match ($this->filter) {
            'today' => $this->getChartForToday(),
            'week' => $this->getChartForLastWeek(),
            'month' => $this->getChartForLastMonth(),
            'year' => $this->getChartForLastYear(),
        };
    }

    /**
     * Specifies the type of chart to be rendered.
     *
     * @return string The chart type ('line' in this case).
     */
    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Retrieves and formats activity chart data for a given period.
     * This is a helper function used by the filter-specific data retrieval methods.
     * It leverages the Flowframe\Trend library to aggregate activity data.
     *
     * @param  Carbon $start    The start of the period.
     * @param  Carbon $end      The end of the period.
     * @param  string $period   The aggregation period (e.g., 'perHour', 'perDay', 'perMonth').
     * @return array<int>       The formatted chart data.
     * @throws Exception        If there's an issue with the $period value.
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

    /**
     * Retrieves chart data for today, aggregated per hour.
     *
     * @return array<int> The chart data for today.
     */
    private function getChartForToday(): array
    {
        return $this->getActivityChartData(now()->startOfDay(), now()->endOfDay(), 'perHour');
    }

    /**
     * Retrieves chart data for the last week, aggregated per day.
     *
     * @return array<int> The chart data for the last week.
     */
    private function getChartForLastWeek(): array
    {
        return $this->getActivityChartData(now()->startOfWeek(), now()->endOfWeek(), 'perDay');
    }

    /**
     * Retrieves chart data for the last month, aggregated per day.
     *
     * @return array<int> The chart data for the last month.
     */
    private function getChartForLastMonth(): array
    {
        return $this->getActivityChartData(now()->startOfMonth(), now()->endOfMonth(), 'perDay');
    }

    /**
     * Retrieves chart data for the last year, aggregated per month.
     *
     * @return array<int> The chart data for the last year.
     */
    private function getChartForLastYear(): array
    {
        return $this->getActivityChartData(now()->startOfYear(), now()->endOfYear(), 'perMonth');
    }
}
