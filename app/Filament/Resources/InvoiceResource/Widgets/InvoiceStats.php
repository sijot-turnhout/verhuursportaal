<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Filament\Resources\InvoiceResource\Pages\ListInvoices;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class InvoiceStats extends StatsOverviewWidget
{
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListInvoices::class;
    }

    protected function getStats(): array
    {
        return [
            $this->getInvoiceTotalStatWidget(),
            $this->getInvoiceIncomeTotalWidget(),
            $this->getUnpaidInvoicesTotalWidget(),
            $this->getInvoicePropoalsWidget(),
        ];
    }

    private function getInvoiceTotalStatWidget(): Stat
    {
        $invoiceData = Trend::model(Invoice::class)
            ->between(start: now()->subYear(), end: now())
            ->perMonth()
            ->count();

        return Stat::make(trans('Aantal facturen'), Invoice::query()->count())
            ->chart($invoiceData->map(fn(TrendValue $value) => $value->aggregate)->toArray())
            ->color('success');
    }

    private function getInvoiceIncomeTotalWidget(): Stat
    {
        $query = Invoice::query()->where('status', InvoiceStatus::Paid);

        return Stat::make(trans('Betaalde facturen'), $query->count());
    }

    private function getUnpaidInvoicesTotalWidget(): Stat
    {
        return Stat::make(
            label: trans('Achterstallige facturen'),
            value: Invoice::query()->where('status', InvoiceStatus::Uncollected)->count(),
        );
    }

    private function getInvoicePropoalsWidget(): Stat
    {
        return Stat::make(
            label: trans('Facturatie voorstellen'),
            value: Invoice::query()->where('status', InvoiceStatus::Draft)->count(),
        );
    }
}
