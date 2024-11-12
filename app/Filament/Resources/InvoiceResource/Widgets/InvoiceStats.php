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

/**
 * Class InvoiceStats
 *
 * This class defines the statistical widgets for the Invoice Resource in the Filament Admin Panel.
 * It provides several stats such as total invoices, paid invoices, unpaid invoices, and invoice proposals (drafts).
 * The widgets can be extended or customized to include additional statistical data as needed.
 *
 * Key Features:
 * - Displays total invoices with a year-long trend graph.
 * - Provides widgets for paid and unpaid invoices.
 * - Shows the number of draft invoice proposals.
 * - Polling interval can be adjusted if auto-refresh is needed.
 *
 * @package App\Filament\Resources\InvoiceResource\Widgets
 */
final class InvoiceStats extends StatsOverviewWidget
{
    /**
     * The polling interval for the widget to auto-refresh data.
     *
     * @var string|null
     */
    protected static ?string $pollingInterval = null;

    /**
     * Retrieves the class responsible for handling the page where invoices are listed.
     *
     * @return string The page class handling the invoice table.
     */
    protected function getTablePage(): string
    {
        return ListInvoices::class;
    }

    /**
     * Retrieves and groups all statistical metrics (widgets) for the stats overview.
     *
     * This method returns an array of different stats that are displayed in the dashboard.
     * Each stat is generated by a separate method, keeping the code modular.
     *
     * @return array<int, Stat> An array of Stat instances representing different statistics.
     */
    protected function getStats(): array
    {
        return [
            $this->getInvoiceTotalStatWidget(),
            $this->getInvoiceIncomeTotalWidget(),
            $this->getUnpaidInvoicesTotalWidget(),
            $this->getInvoicePropoalsWidget(),
        ];
    }

    /**
     * Generates a widget to show the total number of invoices over the past year.
     * This stat also includes a chart representing the monthly count of invoices.
     *
     * @return Stat A Stat widget displaying the total number of invoices.
     */
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

    /**
     * Generates a widget that displays the total number of paid invoices.
     * This stat counts invoices marked as "Paid" using the InvoiceStatus enum.
     *
     * @return Stat A Stat widget showing the count of paid invoices.
     */
    private function getInvoiceIncomeTotalWidget(): Stat
    {
        $query = Invoice::query()->where('status', InvoiceStatus::Paid);

        return Stat::make(trans('Betaalde facturen'), $query->count());
    }

    /**
     * Generates a widget that shows the total number of unpaid invoices.
     * Unpaid invoices are determined by the "Uncollected" status in the InvoiceStatus enum.
     *
     * @return Stat A Stat widget displaying the count of unpaid invoices.
     */
    private function getUnpaidInvoicesTotalWidget(): Stat
    {
        return Stat::make(
            label: trans('Achterstallige facturen'),
            value: Invoice::query()->where('status', InvoiceStatus::Uncollected)->count(),
        );
    }

    /**
     * Generates a widget that shows the number of invoice proposals (drafts).
     * Draft invoices are represented by the "Draft" status in the InvoiceStatus enum.
     *
     * @return Stat A Stat widget showing the count of invoice drafts.
     */
    private function getInvoicePropoalsWidget(): Stat
    {
        return Stat::make(
            label: trans('Facturatie voorstellen'),
            value: Invoice::query()->where('status', InvoiceStatus::Draft)->count(),
        );
    }
}
