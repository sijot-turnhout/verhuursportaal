<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Actions;

use App\Models\Deposit;
use App\Models\Lease;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Gate;

/**
 * Class RegisterDepositAction
 *
 * This action allows administrators to register a deposit payment associated with a lease.
 * It displays a form where users can input deposit details, including the amount, payment date,
 * and the deadline for refunding the deposit. Access to this action is restricted by permissions.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\Actions
 */
final class RegisterDepositAction extends Action
{
    /**
     * Creates and configures the deposit registration action.
     * It sets up a form modal that prompts users to enter deposit payment information.
     *
     * @param  string|null $name  Optional name for the action, with a default label for deposit registration.
     * @return static             Configured instance of the RegisterDepositAction.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Waarborg registreren'))
            ->visible(fn(Lease $lease) => Gate::allows('configure-deposit', $lease))
            ->modalHeading('Huurwaarborg betaling registreren')
            ->modalIcon('heroicon-o-cog-8-tooth')
            ->modalIconColor('primary')
            ->modalDescription('Elke verhuring is onderworpen aan een betaling van een waarborg. Hier kunt u de gegevens van de betaling door de groep registreren.')
            ->modalSubmitActionLabel(trans('Opslaan'))
            ->icon('heroicon-o-cog-8-tooth')
            ->form(fn(Lease $record): array => self::getConfigurationForm($record))
            ->action(function (array $data, Lease $record): void {
                $record->deposit()->save(new Deposit(array_merge($data, ['paid_at' => now()])));
            });
    }

    /**
     * Configures the deposit registration form, providing inputs for deposit amount,
     * payment date, and refund deadline.
     *
     * @param  Lease $lease         The lease for which the deposit is being registered, used to set default values.
     * @return array<int, Grid>     The form configuration array, containing the form fields and their settings.
     */
    private static function getConfigurationForm(Lease $lease): array
    {
        return [
            Grid::make(12)
                ->schema([
                    TextInput::make('paid_amount')
                        ->label('Borgsom')
                        ->numeric()
                        ->required()
                        ->default(config()->integer('sijot-verhuur.deposit.default_amount'))
                        ->columnSpan(4)
                        ->autofocus(false)
                        ->prefixIcon('heroicon-o-currency-euro')
                        ->prefixIconColor('primary'),

                    DatePicker::make('paid_at')
                        ->label('Betalingsdatum')
                        ->columnSpan(4)
                        ->required()
                        ->default(now())
                        ->prefixIcon('heroicon-o-calendar')
                        ->prefixIconColor('primary')
                        ->native(false),

                    DatePicker::make('refund_at')
                        ->label('uiterste terugbetalingsdatum')
                        ->columnSpan(4)
                        ->required()
                        ->default($lease->departure_date->addWeeks(2))
                        ->prefixIcon('heroicon-o-calendar')
                        ->prefixIconColor('primary')
                        ->native(false),
                ]),
        ];
    }
}
