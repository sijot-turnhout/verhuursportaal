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
