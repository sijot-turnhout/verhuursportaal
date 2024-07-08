<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Widgets\ChartWidget;

class LineChartBase extends ChartWidget
{
    /**
     * Configuration variable for setting the max heigt of the chart widget in view
     *
     * @var string|null $maxHeight
     */
    protected static ?string $maxHeight = '150px';

    protected static ?string $minHeight = '150px';

    /**
     * The configuration options that rae related to the chart.js package.
     *
     * @see https://www.chartjs.org/docs/latest/api/
     * @var array<string, mixed> | null $options
     */
    protected static ?array $options = [
        'scales' => [
            'y' => ['stacked' => true, 'display' => false, 'beginAtZero' => true, 'ticks' => ['stepSize' => 1]],
            'x' => ['stacked' => true],
        ],
        'plugins' => ['legend' => ['display' => true, 'fill' => true]],
    ];

    /**
     * The chart type to display in the component.
     *
     * @return string
     */
    protected function getType(): string
    {
        return 'bar';
    }
}
