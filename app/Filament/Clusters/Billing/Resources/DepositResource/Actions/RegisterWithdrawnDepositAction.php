<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Actions;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Gate;

/**
 * Class RegisterWithdrawnDepositAction
 *
 * This action allows administrators to register a deposit that is associated with a lease as fully withdrawn.
 * It displays a form where the administrator can input the reason for the withdrawal of the deposit.
 *
 * @package App\Filament\Clusters\Billing\Resources\epositResource\Actions
 */
final class RegisterWithdrawnDepositAction extends Action
{
    /**
     * Creates and configures the deposit withdrawal registration action.
     * It sets up a from modal that prompts administrators to enter a vild reason for the deposit withdrawal.
     *
     * @param  string|null $name  Optional name for the action, with a default label for the registration action
     * @return static              Configured instance of the RegisterWithdrawnAction.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Ingetrokken waarborg'))
            ->color('danger')
            ->icon('heroicon-o-credit-card')
            ->modalHeading('Waarborg registreren als ingetrokken')
            ->modalIcon('heroicon-o-credit-card')
            ->modalDescription('Met deze modal kun je een volledige intrekking
                van de waarborg registreren voor de verhuring in geval van beschadigingen tijdens de verhuring.
                Na deze registratie is het niet meer mogelijk om de terugbetaling te wijzigen.')
            ->visible(fn(Deposit $deposit): bool => Gate::allows('mark-as-fully-withdrawn', $deposit))
            ->modalSubmitActionLabel('Registreren')
            ->form(self::configureModalForm())
            ->action(fn(array $data, Deposit $record): bool => $record->initiateWithdrawal($data));
    }

    /**
     * Configure the form schema for a modal used in partial deposit registrations.
     *
     * This method sets up a form with fields displaying relevant deposit information, such as the lease reference
     * and the paid deposit amount. It also includes a text area for providing the reason for a partial refund.
     * These details aid administrators in completing accurate and well-documented entries.
     *
     * @return array<int, Grid>  The configuration schema for the form elements within the modal.
     */
    private static function configureModalForm(): array
    {
        return [
            Grid::make(12)
                ->schema([
                    TextInput::make('Verhurings referentie')
                        ->columnSpan(6)
                        ->translateLabel()
                        ->prefixIcon('heroicon-o-home-modern')
                        ->default(fn(Deposit $deposit): string => $deposit->lease->reference_number ?? '-')
                        ->prefixIconColor('primary')
                        ->disabled(),

                    TextInput::make('paid_amount')
                        ->label('Betaalde waarborg')
                        ->numeric()
                        ->required()
                        ->default(fn(Deposit $deposit): float|string => $deposit->paid_amount)
                        ->columnSpan(6)
                        ->disabled()
                        ->prefixIcon('heroicon-o-currency-euro')
                        ->prefixIconColor('primary'),

                    Textarea::make('note')
                        ->label(trans('Reden van de gedeeltelijke terugbetaling'))
                        ->placeholder(trans('Probeer zo duidelijk mogelijk de beslissing te motiveren'))
                        ->helperText(trans('Dit is louter voor administratieve doeleinden en word niet gecommuniceerd naar de huurder.'))
                        ->required()
                        ->rows(4)
                        ->columnSpan(12),
                ]),
        ];
    }
}
