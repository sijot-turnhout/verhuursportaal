<?php

declare(strict_types=1);

namespace App\Builders;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Class InvoiceBuilder
 *
 * This class extends the Eloquent Builder to provide custom query building
 * capabilities for the Invoice model.
 *
 * @template TModelClass of \App\Models\Invoice>
 * @extends Builder<\App\Models\Invoice>
 */
final class InvoiceBuilder extends Builder
{
    /**
     * Complete the proposal by setting the invoice status to 'Open' and
     * updating the 'due_at' date to one month from now, at the end of the day.
     *
     * @return bool true if the update was successful, false otherwise.
     */
    public function completeProposal(): bool
    {
        return $this->model->update([
            'status' => InvoiceStatus::Open,
            'due_at' => now()->addMonth()->endOfDay(),
        ]);
    }

    /**
     * Update the quotation status to the specified InvoiceStatus.
     *
     * @param  InvoiceStatus  $invoiceStatus  The status to set for the invoice.
     * @return bool                           Returns true if the update operation was successful, otherwise false.
     */
    public function markQuotationAs(InvoiceStatus $invoiceStatus, ?Carbon $dueAt = null): bool
    {
        return $this->model->update(['status' => $invoiceStatus, 'quotation_due_at' => $dueAt]);
    }

    /**
     * @return Builder<Invoice>
     */
    public function excludeQuotations(): Builder
    {
        return $this->whereNotIn('status', [InvoiceStatus::Quotation_Request, InvoiceStatus::Quotation, InvoiceStatus::Quotation_Declined]);
    }

    /**
     * @return Builder<Invoice>
     */
    public function onlyQuotations(): Builder
    {
        return $this->whereIn('status', [InvoiceStatus::Quotation_Request, InvoiceStatus::Quotation, InvoiceStatus::Quotation_Declined]);
    }

    /**
     * @return Builder<Invoice>
     */
    public function invoiceProposals(): Builder
    {
        return $this->where('status', InvoiceStatus::Draft);
    }

    /**
     * @return Builder<Invoice>
     */
    public function openInvoices(): Builder
    {
        return $this->where('status', InvoiceStatus::Open);
    }

    /**
     * @return Builder<Invoice>
     */
    public function paidInvoices(): Builder
    {
        return $this->where('status', InvoiceStatus::Paid);
    }

    /**
     * @return Builder<Invoice>
     */
    public function voidedInvoices(): Builder
    {
        return $this->where('status', InvoiceStatus::Void);
    }

    /**
     * @return Builder<Invoice>
     */
    public function uncollectibleInvoices(): Builder
    {
        return $this->where('status', InvoiceStatus::Uncollected);
    }
}
