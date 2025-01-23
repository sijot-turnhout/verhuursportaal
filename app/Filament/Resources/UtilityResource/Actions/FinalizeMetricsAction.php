<?php

declare(strict_types=1);

namespace App\Filament\Resources\UtilityResource\Actions;

use App\Jobs\InvoiceUtilityUsage;
use App\Models\Lease;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

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
            ->modalDescription(self::configureModalDescription())
            /** @phpstan-ignore-next-line */
            ->visible(fn(RelationManager $livewire): bool => $livewire->getOwnerRecord()->canDisplayTheFinalizeButton())
            ->action(fn(RelationManager $livewire): bool => self::performFinalizeMetricsAction($livewire->getOwnerRecord()));
    }

    /**
     * Finalizes utility usage metrics for a given lease and triggers invoice generation.
     *
     * This method ensures that the registration of utility usage metrics is a transactional operation,
     * meaning all changes will either be fully applied or rolled back if an error occurs. Once the
     * metrics are finalized, an asynchronous dispatch is triggered to generate billing items
     * for the utility usage, provided invoicing conditions are met.
     *
     * @param  Model|Lease $lease The lease instance for which utility metrics are being finalized.
     * @return bool               Returns `true` if the metrics were successfully finalized and the lease was updated. Returns `false` if the transaction fails.
     *
     * @throws Throwable If the transaction fails or an error occurs during deferred dispatch.
     */
    private static function performFinalizeMetricsAction(Model|Lease $lease): bool
    {
        return DB::transaction(function () use ($lease) {
            defer(callback: fn(Lease $lease) => InvoiceUtilityUsage::dispatch($lease));

            return $lease->update(['metrics_registered_at' => now()]);
        });
    }

    /**
     * Configures the description for the modal based on automatic invoicing settings.
     *
     * This method generates a dynamic modal description string that informs the user about the
     * consequences of finalizing utility metrics. If automatic invoicing is disabled, the message
     * focuses solely on the inability to make changes post-finalization. If automatic invoicing
     * is enabled, the message also highlights that the utility data will be added to a draft
     * invoice if one exists.
     *
     * @return string The translated modal description string.
     */
    private static function configureModalDescription(): string
    {
        if ( ! config()->boolean('sijot-verhuur.billing.automatic_invoicing', false)) {
            return trans('Na het registreren van het verbruik is het niet meer mogelijk om deze te wijzigen. Vandaar dat we u willen vragen om bij twijfel alles nog is na te kijken.');
        }

        return trans('Na het registreren van het verbruik is het niet meer mogelijk om deze te wijzigen en zullen deze gegevens toegevoegd worden op het facturatievoorstel indien er een is in de applicatie. Vandaar dat we u willen vragen om bij twijfel alles nog is na te kijken.');
        ;
    }
}
