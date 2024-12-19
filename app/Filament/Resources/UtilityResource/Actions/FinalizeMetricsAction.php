<?php

declare(strict_types=1);

namespace App\Filament\Resources\UtilityResource\Actions;

use App\Jobs\InvoiceUtilityUsage;
use App\Models\Lease;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class FinalizeMetricsAction
 *
 * Defines an action for finalizing metrics registration within a Filament relation manager.
 * This action is used to lock or finalize the registration of metrics, making it irreversible.
 * It includes confirmation prompts and provides functionality to update the record with
 * the current timestamp when the action is executed.
 *
 * @package App\Filament\Resources\UtilityResource\Actions
 */
final class FinalizeMetricsAction extends Action
{
    /**
     * Create a new instance of the FinalizeMetricsAction.
     *
     * Configures the action with a default name, icon, and modal description.
     * The action will only be visible if certain conditions are met, and will
     * require confirmation before proceeding. It updates the `metrics_registered_at`
     * field of the owner record with the current timestamp upon execution.
     *
     * @param  string|null  $name  The name of the action. If not provided, defaults to a translatable string.
     * @return static
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Verbruik registreren'))
            ->icon('heroicon-o-lock-closed')
            ->requiresConfirmation()
            ->modalDescription(trans('Na het registreren van het verbruik is het niet meer mogelijk om deze te wijzigen. Vandaar dat we u willen vragen om bij twijfel alles nog is na te kijken.'))
            ->modalDescription('test')
            ->visible(fn(RelationManager $livewire): bool => self::canPerformAction($livewire->getOwnerRecord()))
            ->action(fn(RelationManager $livewire): bool => self::performFinalizeMetricsAction($livewire->getOwnerRecord()));
    }

    /**
     * Method that performs the authorization check for the action.
     *
     * @param  Model|Lease $lease The lease entity from the database.
     * @return bool
     */
    private static function canPerformAction(Model|Lease $lease): bool
    {
        return $lease->canDisplayTheFinalizeButton();
    }

    /**
     * The method that performs the needed logic to perform the finalization of the utility metrics.
     *
     * @param  Model|Lease $lease The lease entity from the database where the finalization happends on.
     * @return bool
     */
    private static function performFinalizeMetricsAction(Model|Lease $lease): bool
    {
        return DB::transaction(function () use ($lease) {
            InvoiceUtilityUsage::dispatch($lease);

            return $lease->update(['metrics_registered_at' => now()]);
        });
    }


}
