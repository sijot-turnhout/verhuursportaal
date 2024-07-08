<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

/**
 * Class ListInvoices
 *
 * This class represents the page for listing invoices within the InvoiceResource.
 * It includes methods for defining header actions, header widgets, and tabs for
 * different invoice statuses.
 *
 * @todo Refactor this class in a later phase.
 */
final class ListInvoices extends ListRecords
{
    use ExposesTableToWidgets;

    /**
     * The resource associated with this page.
     *
     * @var string
     */
    protected static string $resource = InvoiceResource::class;

    public function getTabs(): array
    {
        return [
            null => $this->getAllInvoicesTab(),
            'voorstellen' => $this->getInvoiceProposalsTab(),
            'openstaand' => $this->getOpenInvoicesTab(),
            'betaald' => $this->getPaidInvoicesTab(),
            'geannuleerd' => $this->getVoidInvoicesTab(),
            'onbetaald' => $this->getUncollectedInvoicesTab(),
        ];
    }

    /**
     * Get the tabs available on the page.
     *
     * @return array<int, \Filament\Actions\CreateAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-document-plus'),
        ];
    }

    /**
     * Get the header widgets available on the page.
     *
     * @return array<string>
     */
    protected function getHeaderWidgets(): array
    {
        return InvoiceResource::getWidgets();
    }

    /**
     * Get the tab for all invoices.
     *
     * @return Tab
     */
    private function getAllInvoicesTab(): Tab
    {
        $tab = Tab::make(trans('Alle'))
            ->query(fn(Invoice $builder) => $builder->excludeQuotations())
            ->icon('heroicon-o-list-bullet');

        if (Invoice::query()->excludeQuotations()->count() > 0) {
            $tab->badge(Invoice::query()->count());
        }

        return $tab;
    }

    /**
     * Get the tab for open invoices.
     *
     * @return Tab
     */
    private function getOpenInvoicesTab(): Tab
    {
        $tab = Tab::make()
            ->query(fn(Invoice $builder) => $builder->openInvoices())
            ->icon('heroicon-o-document-text');

        $query = Invoice::query()->openInvoices();

        if ($query->count() > 0) {
            $tab->badge($query->count());
        }

        return $tab;
    }

    /**
     * Get the tab for invoice proposals.
     * @return Tab
     */
    private function getInvoiceProposalsTab(): Tab
    {
        $tab = Tab::make()
            ->query(fn(Invoice $builder) => $builder->invoiceProposals())
            ->icon('heroicon-o-pencil-square');

        $query = Invoice::query()->invoiceProposals();

        if ($query->count() > 0) {
            $tab->badge($query->count());
        }

        return $tab;
    }

    /**
     * Get the tab for paid invoices.
     *
     * @return Tab
     */
    private function getPaidInvoicesTab(): Tab
    {
        $tab = Tab::make()
            ->query(fn(Invoice $invoice) => $invoice->paidInvoices())
            ->icon('heroicon-o-check-circle');

        $query = Invoice::query()->paidInvoices();

        if ($query->count() > 0) {
            $tab->badge($query->count());
        }

        return $tab;
    }

    /**
     * Get the tab for void invoices.
     *
     * @return Tab
     */
    private function getVoidInvoicesTab(): Tab
    {
        $tab = Tab::make()
            ->query(fn(Invoice $invoice) => $invoice->voidedInvoices())
            ->icon('heroicon-o-x-circle');

        $query = Invoice::query()->voidedInvoices();

        if ($query->count() > 0) {
            $tab->badge($query->count());
        }

        return $tab;
    }

    /**
     * Get the tab for uncollected invoices.
     *
     * @return Tab
     */
    private function getUncollectedInvoicesTab(): Tab
    {
        $tab = Tab::make()
            ->query(fn(Invoice $invoice) => $invoice->uncollectibleInvoices())
            ->icon('heroicon-o-exclamation-triangle');

        $invoice = Invoice::uncollectibleInvoices();

        if ($invoice->count() > 0) {
            $tab->badge($invoice->uncollectibleInvoices()->count());
        }

        return $tab;
    }
}
