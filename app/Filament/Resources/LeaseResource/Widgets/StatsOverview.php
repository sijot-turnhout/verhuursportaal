<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Widgets;

use App\Filament\Support\LineChartBase;
use App\Models\ContactSubmission;
use App\Models\Feedback;
use App\Models\Lease;
use App\Models\Quotation;
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
        $quotationChartData = $this->getChartData(Quotation::class);

        return [
            'datasets' => [
                [
                    'label' => 'Verhuringen',
                    'data' => $leaseChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#2C3830',
                    'borderColor' => '#2C3830',
                    'pointBackgroundColor' => '#2C3830',
                ],
                [
                    'label' => 'Offertes',
                    'data' => $quotationChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#1B1E1E',
                    'borderColor' => '#1B1E1E',
                    'pointBackgroundColor' => '#1B1E1E',
                ],
                [
                    'label' => 'Huurders',
                    'data' => $tenantChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#AA8344',
                    'borderColor' => '#AA8344',
                    'pointBackgroundColor' => '#AA8344',
                ],
                [
                    'label' => 'Contactnames',
                    'data' => $contactChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#D3BA75',
                    'borderColor' => '#D3BA75',
                    'pointBackgroundColor' => '#D3BA75',
                ],
                [
                    'label' => trans('Feedback'),
                    'data' => $feedbackChartData->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#47553C',
                    'borderColor' => '#47553C',
                    'pointBackgroundColor' => '#47553C',
                ],

            ],

            'labels' => $leaseChartData->map(fn(TrendValue $value) => $value->date),
        ];
    }
}
