<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\BillingItem;
use App\Models\Lease;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

final readonly class InvoiceUtilityUsage
{
    public static function dispatch(Lease $lease): bool
    {
        if (! config()->boolean('sijot-verhuur.billing.automatic_invoicing') && $lease->invoice()->exists()) {
            return false;
        }

        return $lease
            ->utilityStatistics()
            ->each(function (Utility $metric) use ($lease): void {
                self::addBillinglineToInvoice($lease, $metric);
            });
    }

    private static function addBillinglineToInvoice(Lease $lease, Utility $metric): BillingItem
    {
        return BillingItem::query()->create([
            'creator_id' => Auth::user()->getAuthIdentifier(),
            'billingdocumentable_type' => get_class($lease->invoice),
            'billingdocumentable_id' => $lease->invoice_id,
            'unit_price' => $metric->unit_price,
            'name' => $metric->name->getBillingLine(),
            'quantity' => $metric->usage_total,
        ]);
    }
}
