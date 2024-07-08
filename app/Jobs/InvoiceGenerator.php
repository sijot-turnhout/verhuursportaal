<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\InvoiceDocumentProcessorInterface;
use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use App\Models\BillingItem;
use App\Models\Invoice;
use App\Services\FinancialDocumentCreator;
use Illuminate\Support\Facades\DB;

final class InvoiceGenerator extends FinancialDocumentCreator implements InvoiceDocumentProcessorInterface
{
    public function handle(): Invoice
    {
        /** @phpstan-ignore-next-line */
        return DB::transaction(fn(): Invoice => tap($this->newFinancialDocument(), function (Invoice $invoice): Invoice {
            $invoiceIsAttached = $this->attachFinancialDocumentToLease($invoice);

            if ($this->automaticInvoiceLineImportEnabled() && $invoiceIsAttached) {
                $invoice->invoiceLines()->saveMany([
                    $this->registerGuaranteePaymentDiscount(),
                    $this->registerBookingCosts(),
                ]);
            }

            return $invoice;
        }));
    }

    protected function registerGuaranteePaymentDiscount(): BillingItem
    {
        return $this->createInvoiceLine(attributes: [
            'name' => trans('Inbrengen van de waarborg als vermindering'),
            'type' => BillingType::Discount,
            'unit_price' => $this->retrieveGuaranteePaymentAmount(),
        ]);
    }
}
