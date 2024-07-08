<?php

declare(strict_types=1);

namespace App\Filament\Resources\UtilityResource\Actions;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;

final class FinalizeMetricsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Verbruik registreren'))
            ->icon('heroicon-o-lock-closed')
            ->requiresConfirmation()
            ->modalDescription(trans('Na het registreren van het verbruik is het niet meer mogelijk om deze te wijzigen. Vandaar dat we u willen vragen om bij twijfel alles nog is na te kijken.'))
            ->action(fn(RelationManager $livewire): bool => $livewire->getOwnerRecord()->update(['metrics_registered_at' => now()]))
            /** @phpstan-ignore-next-line */
            ->visible(fn(RelationManager $livewire): bool => $livewire->getOwnerRecord()->canDisplayTheFinalizeButton());
    }
}
