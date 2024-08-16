<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions\PaymentStatus;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

/**
 * Class MarkAsUncollectedAction
 *
 * Defines an action that allows users to mark an invoice as uncollected in the Filament administrative interface.
 *
 * @see \App\Policies\InvoicePolicy::updatePaymentStatus()
 */
final class MarkAsUncollectedAction extends Action
{
    /**
     * Create a new instance of the MarkAsUncollectedAction.
     *
     * This method configures the action button, including its label, icon, color,
     * visibility conditions, and the action to be performed when the button is clicked.
     *
     * @param  string|null  $name  Optional name parameter (not used in this implementation).
     * @return static The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return parent::make('Markeer als achterstallig')
            ->icon('heroicon-o-exclamation-triangle')
            ->color('warning')
            ->visible(fn (Invoice $invoice): bool => self::canMarkAsUncollected($invoice))
            ->action(function (Invoice $invoice): void {
                $invoice->update(['status' => InvoiceStatus::Uncollected]);
                Notification::make()->title(trans('De factuur status is met success aangepast naar verstreken'))->danger()->send();
            });
    }

    /**
     * Method for checking if the currently authenticated user is permitted to perform payment status update.
     *
     * @param  Invoice $invoice The instance of the invoice that needs the paymentStatus update
     * @return bool             True is the user is permitted to perform the update of the payment status
     */
    private static function canMarkAsUncollected(Invoice $invoice): bool
    {
        return Gate::allows('update-payment-status', $invoice)
            && $invoice->status->isNot(InvoiceStatus::Uncollected);
    }
}
