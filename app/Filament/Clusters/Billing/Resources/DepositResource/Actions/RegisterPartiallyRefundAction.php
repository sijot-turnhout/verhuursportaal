<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Actions;

use App\Jobs\Financial\ProcessDepositRefunding;
use App\Models\Deposit;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Columns\TextColumn;

;
use Illuminate\Support\Facades\Gate;

final class RegisterPartiallyRefundAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Gedeeltelijk terugbetaald'))
            ->visible(fn(Deposit $deposit): bool => Gate::allows('mark-as-partially-refunded', $deposit))
            ->modalIcon('heroicon-o-credit-card')
            ->modalHeading('Gedeeltelijke terugbetaling van de waarborg')
            ->modalDescription('Met deze modal kun je een gedeeltelijke terugbetaling
                van de waarborg registreren voor de verhuring. Vul het ingetrokken
                bedrag in zodat wij het kunnen opnemen in de administratie. Na deze
                registratie is het niet meer mogelijk om de terugbetaling te wijzigen.')
            ->modalIconColor('warning')
            ->icon('heroicon-o-credit-card')
            ->modalSubmitActionLabel('Registreren')
            ->form(self::configureModalForm())
            ->action(function (array $data, Deposit $record): void {
                ProcessDepositRefunding::dispatch($data, $record);
            });
    }

    private static function configureModalForm(): array
    {
        return [
            Grid::make(12)->schema([
                TextInput::make('Verhurings referentie')
                    ->columnSpan(4)
                    ->translateLabel()
                    ->prefixIcon('heroicon-o-home-modern')
                    ->default(fn(Deposit $deposit): string => $deposit->lease->reference_number ?? '-')
                    ->prefixIconColor('primary')
                    ->disabled(),
                TextInput::make('paid_amount')
                    ->label('Betaalde waarborg')
                    ->columnSpan(4)
                    ->translateLabel()
                    ->prefixIcon('heroicon-o-currency-euro')
                    ->prefixIconColor('primary')
                    ->default(fn(Deposit $deposit): string => $deposit->paid_amount)
                    ->disabled(),
                TextInput::make('revoked_amount')
                    ->label('Ingetrokken bedrag v/d waarborg')
                    ->columnSpan(4)
                    ->numeric()
                    ->translateLabel()
                    ->required()
                    ->maxValue(fn(Deposit $deposit): string => $deposit->paid_amount)
                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                    ->prefixIcon('heroicon-o-currency-euro')
                    ->prefixIconColor('primary'),
                Textarea::make('note')
                    ->label(trans('Reden van de gedeeltelijke terugbetaling'))
                    ->placeholder(trans('Probeer zo duidelijk mogelijk de beslissing te motiveren'))
                    ->helperText(trans('Dit is louter voor administratieve doeleinden en word niet gecommuniceerd naar de huurder.'))
                    ->required()
                    ->rows(4)
                    ->columnSpan(12)
            ])
        ];
    }
}
