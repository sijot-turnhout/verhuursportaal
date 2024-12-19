<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\BillingItem;
use App\Models\Lease;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

/**
 * Handles the process of creating billing items based on utility usage for a given lease.
 *
 * This class ensures that billing items are generated and added to an associated invoice
 * only when certain conditions are met, such as the presence of utility usage statistics
 * and the configuration settings for automatic invoicing. It also handles scenarios where
 * invoicing should be skipped, such as when an existing draft invoice is already present.
 *
 * @package App\Jobs
 */
final readonly class InvoiceUtilityUsage
{
    /**
     * Dispatch the process to generate billing items for a lease's utility usage.
     *
     * This method verifies whether invoicing should proceed based on the system configuration
     * and the current status of the lease's associated invoice. If invoicing is skipped, the
     * method immediately returns `false`. Otherwise, it iterates over the utility usage statistics
     * for the lease and creates corresponding billing items on the invoice.
     *
     * @param  Lease $lease  The lease instance for which utility billing items should be created.
     * @return bool          Returns `false` if invoicing is skipped due to configuration or draft invoice status.
     *                       Otherwise, returns `true` once utility statistics have been processed.
     *
     * @throws \Exception    If there is an issue while processing the utility statistics or adding billing items.
     */
    public static function dispatch(Lease $lease): bool
    {
        if (self::shouldSkipInvoicing($lease)) {
            return false;
        }

        return $lease->utilityStatistics()->each(fn (Utility $metric) => self::addBillinglineToInvoice($lease, $metric));
    }

    /**
     * Determine whether invoicing should be skipped for the given lease.
     *
     * This method checks the system configuration for automatic invoicing, as well as the
     * current state of the associated invoice for the lease. Invoicing is skipped if:
     *
     * - Automatic invoicing is disabled in the configuration.
     * - An associated invoice exists and its status is set to `Draft`.
     *
     * @param  Lease $lease  The lease instance to evaluate.
     * @return bool          Returns `true` if invoicing should be skipped based on configuration or draft invoice status, otherwise returns `false`.
     */
    private static function shouldSkipInvoicing(Lease $lease): bool
    {
        $automaticInvoicingEnabled = config()->boolean('sijot-verhuur.billing.automatic_invoicing', false);
        $ensureInvoiceExists = $lease->invoice()->exists();
        $ensureDraftInvoice = $lease->invoice->status->is(InvoiceStatus::Draft);

        return ! $automaticInvoicingEnabled && $ensureInvoiceExists && $ensureDraftInvoice;
    }

    /**
     * Add a billing line item to the invoice for a specific utility usage metric.
     *
     * This method creates a new `BillingItem` record based on the provided utility usage data.
     * Each utility metric is converted into an individual billing item, which includes details
     * such as the utility name, unit price, and total usage quantity. The item is associated
     * with the lease's invoice.
     *
     * @param  Lease    $lease      The lease instance associated with the invoice.
     * @param  Utility  $metric     The utility usage metric to be billed.
     * @return BillingItem          The newly created billing item record.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException  If the invoice or associated data cannot be found.
     * @throws \Illuminate\Database\QueryException                   If there is an issue during the database operation.
     */
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
