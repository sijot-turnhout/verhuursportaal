<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions\PaymentStatus;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

/**
 * Class MarkAsVoidedAction
 *
 * Defines an action that allows users to mark an invoice as voided in the Filament administrative interface.
 *
 * @see \App\Policies\InvoicePolicy::updatePaymentStatus()
 */
final class MarkAsVoidedAction extends Action
{
    /**
     * Create a new instance of the MarkAsVoidedAction.
     *
     * This method configures the action button, including its label, icon, color, confirmation requirements,
     * visibility conditions, and the action to be performed when the button is clicked.
     *
     * @param  string|null  $status  Optional status parameter (not used in this implementation).
     * @return static The configured action instance.
     */
    public static function make(?string $status = null): static
    {
        return parent::make('Markeer als geannuleerd')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading(trans('Facturatie annuleren'))
            ->modalDescription(trans('Indien u een factuur annuleerd kan de huurder geen factuur meer ontvangen via de applicatie. En kan deze actie ook niet meer ongedaan gemaakt worden.'))
            ->visible(fn(Invoice $invoice): bool => Gate::allows('update-payment-status', $invoice))
            ->action(function (Invoice $invoice): void {
                $invoice->update(['status' => InvoiceStatus::Void, 'due_at' => null]);
                Notification::make()->title('De factuur is met success geannuleerd')->success()->send();
            });
    }
}
