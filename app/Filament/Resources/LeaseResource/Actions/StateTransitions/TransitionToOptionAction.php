<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions\StateTransitions;

use App\Enums\LeaseStatus;
use App\Filament\Support\Actions\StateTransitionAction;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Handles the transition of a lease to the 'Option' state.
 *
 * This action allows marking a lease as 'Option' is the current state permits the transaction.
 * It includes checks for user permissions and state validation.
 *
 * @package App\Filament\Resources\LeaseResource\Actions\StateTransitions
 */
final class TransitionToOptionAction extends StateTransitionAction
{
    /**
     * Creates a new instance of the action with the specified configuration.
     *
     * @param  string|null $name  The name of the action (optional).
     * @return static             The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return self::buildStateTransitionAction(name: 'transition-to-option', label: 'Markeren als optie', finalState: LeaseStatus::Option)
            ->visible(fn(Lease $lease): bool => self::canTransition($lease))
            ->action(fn(Lease $lease) => self::performActionLogic($lease));
    }

    /**
     * {@inheritDoc}
     */
    public static function canTransition(Model $lease): bool
    {
        /** @phpstan-ignore-next-line */
        return Gate::allows('update', $lease) && $lease->status->in(enums: self::configureAllowedStates());
    }

    /**
     * {@inheritDoc}
     */
    public static function configureAllowedStates(): array
    {
        return [LeaseStatus::Request, LeaseStatus::Quotation];
    }

    /**
     * {@inheritDoc}
     */
    public static function performActionLogic(Model $lease): void
    {
        $lease->state()->transitionToOption();
    }
}
