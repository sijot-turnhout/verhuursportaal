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

final class ConfigureDepositAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('configureren'))
            ->visible(fn (Lease $lease) => Gate::allows('configure-deposit', $lease))
            ->modalHeading('Huurwaarborg configureren')
            ->modalIcon('heroicon-o-cog-8-tooth')
            ->modalIconColor('primary')
            ->modalDescription('Elke verhuring is onderworpen aan een betaling van een waarborg. Hier kunt het de benodigde gegevens omtrent een waarborg instellen. Tenzij deze is vrijgesteld door bv een verhuring aan eigen groep.')
            ->modalSubmitActionLabel(trans('Opslaan'))
            ->icon('heroicon-o-cog-8-tooth')
            ->form(fn(Lease $record): array => self::getConfigurationForm($record))
            ->action(function (array $data,  Lease $record): void {
                $record->deposit()->save(
                    new Deposit($data)
                );
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

                    DatePicker::make('due_at')
                        ->label('Uiterste betalingsdatum')
                        ->columnSpan(4)
                        ->required()
                        ->default($lease->arrival_date->subDays(3))
                        ->prefixIcon('heroicon-o-calendar')
                        ->prefixIconColor('primary')
                        ->native(false),

                    DatePicker::make('refund_at')
                        ->label('Terugbetalingsdatum')
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
