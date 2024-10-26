<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions;

use App\Models\Invoice;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * Class CompleteInvoiceProposalAction
 *
 * This class defines an action for completing an invoice proposal
 * within the Filament admin panel. It extends the base Action class
 * provided by Filament.
 */
final class CompleteInvoiceProposalAction extends Action
{
    /**
     * Create a new instance of the action.
     *
     * This method configures the action with various settings such as
     * the name, icon, color, confirmation requirements, visibility, and
     * the actual action logic to be performed.
     *
     * @param  string|null  $name  Optional name for the action. Defaults to a translated string if null.
     * @return static The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('voorstel afsluiten'))
            ->icon('heroicon-o-document-check')
            ->color('gray')
            ->requiresConfirmation()
            ->modalHeading(trans('Facturatie voorstel afsluiten'))
            ->modalDescription(trans('Bent u zeker dat u het voorstel wilt afsluiten? Indien het voorstel is afgesloten bent u niet meer in staat om de factuur te wijzigen.'))
            ->modalSubmitActionLabel(trans('Ja, ik ben zeker'))
            ->visible(fn(Invoice $record): bool => Gate::allows('finalize-invoice-draft', $record))
            ->action(fn(Invoice $invoice): bool => $invoice->state()->transitionToOpen());
    }
}
