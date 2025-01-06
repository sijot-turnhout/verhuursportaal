<?php

declare(strict_types=1);

namespace App\Policies;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\User;

/**
 * Class InvoicePolicy
 *
 * This policy class defines the authorization rules for interacting with Invoice records.
 * It ensures that only authorized users (based on their roles) can perform actions
 * such as updating, deleting, finalizing, voiding, and marking invoices as paid.
 *
 * The `before` method checks if the user is part of a privileged group (RVB or Webmaster)
 * and grants or denies access accordingly. Other methods define more specific authorization
 * rules based on the invoice's status and other factors.
 *
 * @package App\Policies
 */
final readonly class InvoicePolicy
{
    /**
     * This method is executed before any other policy method.
     * It grants immediate access to users with RVB or Webmaster roles and denies access otherwise.
     *
     * @param  User $user  The authenticated user attempting the action.
     * @return bool|null   Returns null to allow further policy checks or false to deny access.
     */
    public function before(User $user): ?bool
    {
        if ($user->user_group->isRvb() || $user->user_group->isWebmaster()) {
            return null; // Continue to othuer policy checks
        }

        return false; // Denu access for other user groups
    }

    /**
     * Determine whether the user can update a draft invoice.
     * Only invoices with a draft status can be updated.
     *
     * @param  User    $user     The authenticated user attempting to update the invoice.
     * @param  Invoice $invoice  The invoice being checked for the update action.
     * @return bool              Returns true if the invoice is a draft, false otherwise.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        return in_array($invoice->status, [InvoiceStatus::Draft], true);
    }

    /**
     * Determine whether the user can delete a draft invoice.
     * Only draft invoices are eligible for deletion.
     *
     * @param  User    $user     The authenticated user attempting to delete the invoice.
     * @param  Invoice $invoice  The invoice being checked for deletion.
     * @return bool              Returns true if the invoice is a draft, false otherwise.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        return InvoiceStatus::Draft === $invoice->status;
    }

    /**
     * Determine whether the user can download the invoice.
     * Draft invoices cannot be downloaded.
     *
     * @param  User    $user     The authenticated user attempting to download the invoice.
     * @param  Invoice $invoice  The invoice being checked for the download action.
     * @return bool              Returns true if the invoice is not a draft, false otherwise.
     */
    public function downloadInvoice(User $user, Invoice $invoice): bool
    {
        return InvoiceStatus::Draft !== $invoice->status;
    }

    /**
     * Determine whether the user can finalize an invoice draft.
     * Only draft invoices can be finalized.
     *
     * @param  User    $user     The authenticated user attempting to finalize the draft.
     * @param  Invoice $invoice  The invoice being checked for finalization.
     * @return bool              Returns true if the invoice is a draft, false otherwise.
     */
    public function finalizeInvoiceDraft(User $user, Invoice $invoice): bool
    {
        return $invoice->status->is(InvoiceStatus::Draft) && $invoice->invoiceLines->count() > 0;
    }

    /**
     * Determine whether the user can mark an invoice as void.
     * An invoice can only be marked as void if it is not already void and its status is either 'open' or 'uncollected'.
     *
     * @param  User    $user     The authenticated user attempting to void the invoice.
     * @param  Invoice $invoice  The invoice being checked for voiding.
     * @return bool              Returns true if the invoice is eligible to be voided, false otherwise.
     */
    public function markAsVoid(User $user, Invoice $invoice): bool
    {
        return $invoice->status->isNot(InvoiceStatus::Void)
            && $invoice->status->in([InvoiceStatus::Open, InvoiceStatus::Uncollected]);
    }

    /**
     * Determine whether the user can mark an invoice as uncollected.
     * The invoice must be past due and have an open status to be marked as uncollected.
     *
     * @param  User    $user     The authenticated user attempting to mark the invoice as uncollected.
     * @param  Invoice $invoice  The invoice being checked for this action.
     * @return bool              Returns true if the invoice is eligible to be marked as uncollected, false otherwise.
     */
    public function markAsUncollected(User $user, Invoice $invoice): bool
    {
        return $invoice->status->isNot(InvoiceStatus::Uncollected)
            && optional($invoice->due_at)->isPast()
            && $invoice->status->is(InvoiceStatus::Open);
    }

    /**
     * Determine whether the user can mark an invoice as paid.
     * Invoices that are marked as 'open' or 'uncollected' can be marked as paid.
     *
     * @param  User    $user     The authenticated user attempting to mark the invoice as paid.
     * @param  Invoice $invoice  The invoice being checked for this action.
     * @return bool              Returns true if the invoice is eligible to be marked as paid, false otherwise.
     */
    public function markAsPaid(User $user, Invoice $invoice): bool
    {
        return $invoice->status->isNot(InvoiceStatus::Paid)
            && $invoice->status->in([InvoiceStatus::Uncollected, InvoiceStatus::Open]);
    }
}
