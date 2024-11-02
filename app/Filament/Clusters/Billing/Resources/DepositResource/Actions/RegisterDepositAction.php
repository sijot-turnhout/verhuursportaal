<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Actions;

use App\Models\Deposit;
use App\Models\Lease;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Gate;

final class RegisterDepositAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Betaling registreren'))
            ->visible(fn (Lease $lease) => Gate::allows('configure-deposit', $lease))
            ->modalHeading('Huurwaarborg registreren')
            ->modalIcon('heroicon-o-cog-8-tooth')
            ->modalIconColor('primary')
            ->modalDescription('Elke verhuring is onderworpen aan een betaling van een waarborg. Hier kunt u de gegevens van de betaling door de groep registreren.')
            ->modalSubmitActionLabel(trans('Opslaan'))
            ->icon('heroicon-o-cog-8-tooth')
            ->form(fn(Lease $record): array => self::getConfigurationForm($record))
            ->action(function (array $data,  Lease $record): void {
                $record->deposit()->save(new Deposit($data));
            });
    }

    private static function getConfigurationForm(Lease $lease): array
    {
        return [
            Grid::make(12)
                ->schema([
                    TextInput::make('amount')
                        ->label('Borgsom')
                        ->numeric()
                        ->required()
                        ->default(350)
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
                ])
            ];
    }
}
