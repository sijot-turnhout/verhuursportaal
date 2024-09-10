<?php

declare(strict_types=1);

namespace App\Filament\Resources\UtilityResource\Actions;

use App\Jobs\RegisterInitialUtilityMetrics;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;

/**
 * Class InitializeMetricsAction
 *
 * Defines an action for initializing utility metrics within a Filament relation manager.
 * This action dispatches a job to register initial utility metrics for the owner record
 * when executed. It configures the action with an icon and a default name.
 *
 * @package App\Filament\Resources\UtilityResource\Actions
 */
final class InitializeMetricsAction extends Action
{
    /**
     * Create a new instance of the InitializeMetricsAction.
     *
     * Configures the action with a default name, icon, and action functionality.
     * The action dispatches a job to register initial utility metrics for the owner
     * record when executed.
     *
     * @param  string|null  $name  The name of the action. If not provided, defaults to a translatable string.
     * @return static
     */
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
