<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Widgets;

use App\Filament\Support\LineChartBase;
use App\Models\ContactSubmission;
use App\Models\Feedback;
use App\Models\Lease;
use App\Models\Tenant;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;

/**
 * Class StatsOverview
 *
 * Represents a line chart widget that provides an overview of various statistics related to leases,
 * tenants, contact submissions, and feedback. This widget is part of the Filament resource management system
 * and displays data on a yearly basis.$
 *
 * @package App\FIlament\Resources\LeaseResource\Widgets
 */
final class StatsOverview extends LineChartBase
{
    /**
     * Method for getting the dataset for the chart in the backend.
     *
     * @param  string $modelClass The class string from tyhe database model that will be used for the dataset.
     * @return Collection<int, TrendValue>
     */
    public function getChartData(string $modelClass): Collection
    {
        return Trend::model($modelClass)
            ->between(start: now()->startOfYear(), end: now()->endOfYear())
            ->perMonth()
            ->count();
    }

    /**
     * Method to register the chart widget heading.
     *
     * @return string|Htmlable|null
     */
    public function getHeading(): string|Htmlable|null
    {
        return trans('Nieuwe registraties');
    }

    /**
     * Method to register the description of the chartw widget component.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return trans('Statistiesche weergave van alle nieuwe registraties in :app op jaarbasis', [
            'app' => config('app.name', 'Laravel'),
        ]);
    }
    /**
     * Method to compose the chart data and dataset for the chart widget.
     *
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $leaseChartData = $this->getChartData(Lease::class);
        $tenantChartData = $this->getChartData(Tenant::class);
        $contactChartData = $this->getChartData(ContactSubmission::class);
        $feedbackChartData = $this->getChartData(Feedback::class);

        return [
            'datasets' => [
                [
                    'label' => 'Aanvragen',
                    'data' => $leaseChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#84cc16',
                    'borderColor' => '#84cc16',
                    'pointBackgroundColor' => '#84cc16',
                ],
                [
                    'label' => 'Huurders',
                    'data' => $tenantChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#4338ca',
                    'borderColor' => '#4338ca',
                    'pointBackgroundColor' => '#4338ca',
                ],
                [
                    'label' => 'Contactnames',
                    'data' => $contactChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#be123c',
                    'borderColor' => '#be123c',
                    'pointBackgroundColor' => '#be123c',
                ],
                [
                    'label' => trans('Feedback'),
                    'data' => $feedbackChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#083344',
                    'borderColor' => '#083344',
                    'pointBackgroundColor' => '#083344',
                ],

            ],

            'labels' => $leaseChartData->map(fn(TrendValue $value) => $value->date),
        ];
    }
}
