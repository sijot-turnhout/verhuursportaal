<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Actions;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

/**
 * Class MarkAsDeclinedAction
 *
 * This final class defines the action for marking an invoice as declined within the Filament admin panel.
 * It configures the action button with a specific color, icon, visibility condition, and action logic.
 */
final class MarkAsDeclinedAction extends Action
{
    /**
     * Creates and configures the action for marking an invoice as declined.
     *
     * @param  string|null  $name  The name of the action. If not provided, it defaults to a translated string.
     * @return static              The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Offerte afwijzen'))
            ->color('danger')
            ->visible(fn(Invoice $invoice): bool => Gate::allows('decline-quotation', $invoice))
            ->icon('heroicon-o-x-circle')
            ->action(function (Invoice $invoice): void {
                $invoice->markQuotationAs(InvoiceStatus::Quotation);
                Notification::make()->title('Offerte status gewijzigd')->body('De offerte is met success afgerond. Vergeet zeker niet om deze te downloaden en door te sturen naar de huurder')->success()->send();
            });
    }

}
