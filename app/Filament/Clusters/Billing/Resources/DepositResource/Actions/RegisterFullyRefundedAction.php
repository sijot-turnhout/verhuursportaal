<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Actions;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * Class RegisterFullyRefundedAction
 *
 * This class defines a custom action to register a deposit as fully refunded within the DepositResource.
 * It provides a modal interface that prompts users to confirm before marking the deposit as refunded,
 * updating its status and refund details accordingly.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\Actions
 */
final class RegisterFullyRefundedAction extends Action
{
    /**
     * Creates a new instance of the fully refunded registration action.
     * This action is only visible when the deposit status is "Paid" and enables users to register a full refund.
     *
     * @param  string|null $name  Optional name for the action.
     * @return static             Configured instance of the RegisterFullyRefundedAction.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name = trans('Volledig terugbetaald'))
            ->icon('heroicon-o-credit-card')
            ->visible(fn (Deposit $deposit): bool => Gate::allows('mark-as-fully-refunded', $deposit))
            ->action(fn (Deposit $deposit) => self::processRefundRegistration($deposit))
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading(trans('Volledige terugbetaling registreren'))
            ->modalDescription(trans('
                U staat op het punt een terug betaling van een waarborg te registreren als een volledige
                terugbetaling. Weet u zeker dat je dit wilt doen? Na de registratie is het niet meer mogelijk om de status te wijzigen.
            '));
    }

    /**
     * Registers the deposit as fully refunded by updating its status, refund date, and refunded amount.
     *
     * @param  Deposit $deposit The deposit record being refunded.
     * @return void
     */
    private static function processRefundRegistration(Deposit $deposit): void
    {
        $deposit->update(attributes:
            ['status' => DepositStatus::FullyRefunded, 'refunded_at' => now(), 'refunded_amount' => $deposit->paid_amount]
        );
    }
}
