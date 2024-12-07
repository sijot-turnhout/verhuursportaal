<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Actions;

use App\Filament\Resources\QuotationResource;
use App\Models\Quotation;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

/**
 * Class MarkAsFinalizedAction
 *
 * This final class defines the action for marking an invoice as finalized within the Filament admin panel.
 * It configures the action button with a specific color, icon, visibility condition, and a confirmation requirement.
 * Upon execution, it updates the invoice status and sends a success notification.
 */
final class MarkAsFinalizedAction extends Action
{
    /**
     * Creates and configures the action for marking an invoice as declined.
     *
     * @param  string|null  $name  The name of the action. If not provided, it defaults to a translated string.
     * @return static              The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('offerte afronden'))
            ->color('gray')
            ->icon('heroicon-o-clipboard-document-check')
            ->visible(fn(Quotation $quotation): bool => Gate::allows('finalize', $quotation))
            ->requiresConfirmation()
            ->modalDescription(trans('Indien u de offerte afrond eal het niet meer mogelijk zijn om deze aan te passen. Dus kijk alles nog is goed na bij twijfel.'))
            ->successRedirectUrl(fn(Quotation $quotation): string => QuotationResource::getUrl('view', ['record' => $quotation]))
            ->form([
                SignaturePad::make('signature'),
            ])
            ->action(function (array $data, Quotation $quotation): void {
                DB::transaction(function () use ($data, $quotation): void {
                    $quotation->state()->transitionToOpen();
                });

                Notification::make()->title('Offerte status gewijzigd')->body(trans('De offerte staat u geregistreerd als een openstaande offerte'))->success()->send();
            });
    }
}
