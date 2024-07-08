<?php

declare(strict_types=1);

namespace App\Filament\Resources\UtilityResource\Actions;

use App\Jobs\RegisterInitialUtilityMetrics;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;

final class InitializeMetricsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Verbruik initialiseren'))
            ->icon('heroicon-o-arrow-path')
            ->action(function (RelationManager $livewire): void {
                /** @phpstan-ignore-next-line */
                RegisterInitialUtilityMetrics::dispatch($livewire->getOwnerRecord());
            });
    }
}
