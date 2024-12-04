<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions\StateTransitions;

use App\Enums\LeaseStatus;
use App\Filament\Support\Actions\StateTransitionAction;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

final class TransitionToFinalizedAction extends StateTransitionAction
{
    public static function make(?string $name = null): static
    {
        return self::buildStateTransitionAction(name: 'transition-to-finalized', label: 'Markeren als afgelopen', finalState: LeaseStatus::Finalized)
            ->visible(fn(Lease $lease): bool => self::canTransition($lease))
            ->action(fn(Lease $lease) => self::performActionLogic($lease));
    }

    /**
     * Method to check if the user is authorized to perform the action.
     *
     * @param  Lease $model The resource entity to check against.
     * @return bool
     */
    public static function canTransition(Model $model): bool
    {
        return Gate::allows('update', $model) && $model->status->in(enums: self::configureAllowedStates());
    }

    /**
     * {@inheritDoc}
     */
    public static function configureAllowedStates(): array
    {
        return [LeaseStatus::Confirmed];
    }

    /**
     * Method to perform the state transition logic that couples with this action class.
     *
     * @param  Lease $lease The resource entity where the state transition happends on.
     * @return void
     */
    public static function performActionLogic(Model $lease): void
    {
        $lease->state()->transitionToCompleted();
    }
}
