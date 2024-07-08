<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FinancialDocumentCreatorContract;
use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\BillingItem;
use App\Models\Invoice;
use App\Models\Lease;
use App\Support\Features;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;

class FinancialDocumentCreator implements FinancialDocumentCreatorContract
{
    use Dispatchable;

    public function __construct(
        public Lease $lease,
        public string $invoiceDescription,
    ) {}

    public function automaticInvoiceLineImportEnabled(): bool
    {
        return Features::enabled(Features::automaticBillingLinesImport());
    }

    public function calculateTotalAmountOfNights(): int
    {
        $arrivalDate = $this->lease->arrival_date;
        $departureDate = $this->lease->departure_date;
        $amountOfDays = ($arrivalDate->isSameDay($departureDate) ? 1 : (int) $arrivalDate->diffInDays($departureDate));

        return $amountOfDays * ($this->lease->persons ?? 0);
    }

    public function registerBookingCosts(): BillingItem
    {
        return $this->createInvoiceLine(attributes: [
            'name' => trans('verblijfskosten'),
            'quantity' => $this->calculateTotalAmountOfNights(),
            'unit_price' => $this->retrievePricePerPersonPerNight(),
        ]);
    }

    public function newFinancialDocument(?Carbon $quotationDueAt = null, InvoiceStatus $invoiceStatus = InvoiceStatus::Draft): Invoice|Model
    {
        return Invoice::query()->create([
            'creator_id' => optional(auth()->user())->getAuthIdentifier(),
            'status' => $invoiceStatus,
            'lease_id' => $this->lease->getKey(),
            'customer_id' => $this->lease->tenant->getKey(),
            'description' => $this->invoiceDescription,
            'quotation_due_at' => $quotationDueAt,
        ]);
    }

    public function attachFinancialDocumentToLease(Invoice $generatedInvoice): bool
    {
        return $this->lease->invoice()->associate($generatedInvoice)->save();
    }

    protected function retrievePricePerPersonPerNight(): float|int
    {
        return (int) config('sijot-verhuur.billing.price_per_night');
    }

    protected function retrieveGuaranteePaymentAmount(): int
    {
        return (int) config('sijot-verhuur.billing.guarantee_payment_amount');
    }

    /**
     * @param array{name: string, quantity?: int, unit_price: float|int} $attributes
     * @return BillingItem
     */
    protected function createInvoiceLine(array $attributes): BillingItem
    {
        return new BillingItem($attributes);
    }
}
