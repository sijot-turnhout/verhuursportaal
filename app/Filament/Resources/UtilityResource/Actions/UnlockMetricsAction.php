<?php

declare(strict_types=1);

namespace App\Filament\Resources\UtilityResource\Actions;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * Class UnlockMetricsAction
 *
 * Defines an action for unlocking utility metrics within a Filament relation manager.
 * This action allows the metrics of the owner record to be unlocked. It configures the
 * action with a specific color, icon, and visibility based on permissions.
 *
 * @package App\Filament\Resources\UtilityResource\Actions
 *
 * @see \App\Policies\LeasePolicy::unlockMetrics()  Provides the policy method to check if the action can be performed.
 */
final class UnlockMetricsAction extends Action
{
    /**
     * Create a new instance of the UnlockMetricsAction.
     *
     * Configures the action with a default name, icon, color, and visibility based on
     * the authorization check. When executed, it unlocks the metrics for the owner record.
     *
     * @param  string|null  $name  The name of the action. If not provided, defaults to a translatable string.
     * @return static
     */
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
