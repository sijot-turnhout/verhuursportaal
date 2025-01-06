<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Actions;

use App\Models\Quotation;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

/**
 * Class MarkAsApprovedAction
 *
 * Represents an action that allows marking a quotation as approved. This action is intended
 * to be used in the context of a Filament resource where an invoice or quotation can be
 * approved by authorized users. It is typically displayed as a button in the Filament UI.
 *
 * @package App\Filament\Resources\QuotationResource\Actions
 */
final class MarkAsApprovedAction extends Action
{
    /**
     * Creates a new instance of the MarkAsApprovedAction.
     *
     * @param  string|null $name  Optional name for the action. If not provided, a default translation for 'offerte goedkeuren' will be used.
     * @return static             A new instance of MarkAsApprovedAction.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('offerte goedkeuren'))
            ->icon('heroicon-o-check')
            ->visible(fn(Quotation $quotation): bool => Gate::allows('approve', $quotation))
            ->color('gray')
            ->action(function (Quotation $quotation): void {
                $quotation->state()->transitionToAccepted();
                Notification::make()->title('Offerte status gewijzigd')->body(trans('De offerte staat geregistreerd als goedgekeurd'))->success()->send();
            });
    }
}
