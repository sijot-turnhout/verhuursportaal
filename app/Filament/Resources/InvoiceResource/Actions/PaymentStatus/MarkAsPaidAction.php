<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions\PaymentStatus;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

/**
 * Class MarkAsPaidAction
 *
 * Defines an action that allows users to mark an invoice as paid in the Filament administrative interface.
 *
 * @see \App\Policies\InvoicePolicy::updatePaymentStatus()
 */
final class MarkAsPaidAction extends Action
{
    /**
     * Create a new instance of the MarkAsPaidAction.
     *
     * This method configures the action button, including its label, icon, color,
     * visibility conditions, and the action to be performed when the button is clicked.
     *
     * @param  string|null  $name  Optional name parameter (not used in this implementation).
     * @return static The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return parent::make('markeer als betaald')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->visible(fn(Invoice $invoice): bool => self::canMarkAsPaid($invoice))
            ->action(function (Invoice $invoice): void {
                $invoice->update(['status' => InvoiceStatus::Paid, 'paid_at' => now(), 'due_at' => null]);
                Notification::make()->title('De factuur is geregistreerd als betaald.')->success()->send();
            });
    }

    /**
     * Method for checking if the currently authenticated user is permitted to perform payment status update.
     *
     * @param  Invoice $invoice The instance of the invoice that needs the paymentStatus update
     * @return bool             True is the user is permitted to perform the update of the payment status
     */
    private static function canMarkAsPaid(Invoice $invoice): bool
    {
        return Gate::allows('update-payment-status', $invoice)
            && $invoice->status->isNot(InvoiceStatus::Paid);
    }
}
