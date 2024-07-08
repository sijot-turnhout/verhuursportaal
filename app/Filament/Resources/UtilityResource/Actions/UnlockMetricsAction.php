<?php

declare(strict_types=1);

namespace App\Filament\Resources\UtilityResource\Actions;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * @see \App\Policies\LeasePolicy::unlockMetrics())
 */
final class UnlockMetricsAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('vrijgeven'))
            ->visible(fn(RelationManager $livewire): bool => Gate::allows('unlock-metrics', $livewire->getOwnerRecord()))
            ->color('danger')
            ->icon('heroicon-o-lock-open')
            /** @phpstan-ignore-next-line */
            ->action(fn(RelationManager $livewire): bool => $livewire->getOwnerRecord()->unlockMetrics());
    }
}
