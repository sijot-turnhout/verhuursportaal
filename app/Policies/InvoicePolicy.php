<?php

declare(strict_types=1);

namespace App\Policies;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\User;

/**
 * Class InvoicePolicy
 *
 * This final, read-only class defines policies for various actions that can be performed on an invoice.
 * It checks whether a user has permission to perform specific actions based on their user group and the status of the invoice.
 */
final readonly class InvoicePolicy
{
    /**
     * Allows a user to update an invoice if the user belongs to the RVB or Webmaster user group and the invoice status is either 'Draft' or 'Quotation Request'.
     *
     * This method checks if the user is either in the RVB or Webmaster group and ensures that the invoice status is either 'Draft' or 'Quotation Request'.
     * If both conditions are met, it allows the invoice to be updated.
     *
     * @param User     $user     The user attempting to update the invoice. The user must be a member of the RVB or Webmaster user group.
     * @param Invoice  $invoice  The invoice to be updated. The invoice status must be either 'Draft' or 'Quotation Request'.
     * @return bool              Returns true if the invoice can be updated, otherwise false.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && in_array($invoice->status, [InvoiceStatus::Draft, InvoiceStatus::Quotation_Request], true);
    }

    /**
     * Allows a user to delete an invoice if the user belongs to the RVB or Webmaster user group and the invoice status is 'Draft'.
     *
     * This method checks if the user is either in the RVB or Webmaster group and ensures that the invoice status is 'Draft'.
     * If both conditions are met, it allows the invoice to be deleted.
     *
     * @param  User     $user     The user attempting to delete the invoice. The user must be a member of the RVB or Webmaster user group.
     * @param  Invoice  $invoice  The invoice to be deleted. The invoice status must be 'Draft'.
     * @return bool               Returns true if the invoice can be deleted, otherwise false.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && InvoiceStatus::Draft === $invoice->status;
    }

    /**
     * Allows a user to download an invoice if the user belongs to the RVB or Webmaster user group and the invoice status is not 'Draft'.
     *
     * This method checks if the user is either in the RVB or Webmaster group and ensures that the invoice status is not 'Draft'.
     * If both conditions are met, it allows the invoice to be downloaded.
     *
     * @param  User     $user     The user attempting to download the invoice. The user must be a member of the RVB or Webmaster user group.
     * @param  Invoice  $invoice  The invoice to be downloaded. The invoice status must not be 'Draft'.
     * @return bool               Returns true if the invoice can be downloaded, otherwise false.
     */
    public function downloadInvoice(User $user, Invoice $invoice): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && InvoiceStatus::Draft !== $invoice->status;
    }

    /**
     * Updates the payment status of an invoice if the user belongs to the RVB or Webmaster user group and the invoice status is not 'Draft', 'Paid', or 'Void'.
     *
     * This method checks if the user is either in the RVB or Webmaster group and ensures that the invoice status is not one of 'Draft', 'Paid', or 'Void'.
     * If both conditions are met, it allows the payment status to be updated.
     *
     * @param  User     $user     The user attempting to update the payment status. The user must be a member of the RVB or Webmaster user group.
     * @param  Invoice  $invoice  The invoice whose payment status is to be updated. The invoice status must not be 'Draft', 'Paid', or 'Void'.
     * @return bool               Returns true if the payment status can be updated, otherwise false.
     */
    public function updatePaymentStatus(User $user, Invoice $invoice): bool
    {
        return $user->user_group->isRvb() || $user->user_group->isWebmaster();
    }
}
