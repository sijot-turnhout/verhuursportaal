<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Lease;
use Illuminate\Support\Facades\DB;

/**
 * Class InvoiceGenerator
 *
 * This class handles the generation of an invoice for a given lease.
 * It provides a static method to process the creation of an invoice and link it to a lease.
 * The entire process is wrapped inside a database transaction to ensure atomicity.
 *
 * @package App\Jobs
 */
final readonly class InvoiceGenerator
{
    /**
     * Processes the creation of an invoice for the given lease.
     *
     * This method generates an invoice for the provided lease and associates it with the lease
     * inside a database transaction. If any part of the transaction fails, all changes will be rolled back.
     *
     * @param  Lease  $lease               The lease for which the invoice is being generated.
     * @param  string $invoiceDescription  A description to include in the invoice.
     * @return void
     */
    public static function process(Lease $lease, string $invoiceDescription): void
    {
        DB::transaction(function () use ($lease, $invoiceDescription): void {
            $invoice = self::generateInvoicePreset($lease, $invoiceDescription);
            $lease->invoice()->associate($invoice)->save();
        });
    }

    /**
     * Generates a new invoice for the provided lease.
     *
     * This private method creates a new invoice for the lease based on the preset values like
     * the authenticated user, lease ID, and customer details. The generated invoice has a due date
     * set four weeks from the current date.
     *
     * @param  Lease   $lease               The lease for which the invoice is being generated.
     * @param  string  $invoiceDescription  The description to include on the invoice.
     * @return Invoice                      The generated invoice model instance.
     */
    private static function generateInvoicePreset(Lease $lease, string $invoiceDescription): Invoice
    {
        return Invoice::query()->create([
            'creator_id' => self::getAuthenticatedUser(),
            'lease_id' => $lease->getKey(),
            'customer_id' => $lease->tenant->getKey(),
            'description' => $invoiceDescription,
            'due_at' => now()->addWeeks(4),
        ]);
    }

    /**
     * Retrieves the ID of the currently authenticated user.
     *
     * This helper method is used to determine which user is responsible for creating the invoice.
     *
     * @return int  The unique identifier from the authenticated user.
     */
    private static function getAuthenticatedUser(): int
    {
        return auth()->id();
    }
}
