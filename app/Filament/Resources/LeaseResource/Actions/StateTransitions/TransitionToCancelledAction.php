<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions\StateTransitions;

use App\Enums\LeaseStatus;
use App\Filament\Support\Actions\StateTransitionAction;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

final class transitionToCancelledAction extends StateTransitionAction
{
    public static function make(?string $name = null): static
    {
        return self::buildStateTransitionAction(name: 'transition-to-cacnelled', label: 'Markeren als geannuleerd', finalState: LeaseStatus::Cancelled)
            ->visible(fn(Lease $lease): bool => self::canTransition($lease))
            ->action(fn(Lease $lease) => self::performActionLogic($lease));
    }

    /**
     * Method to check if the user is authorized to perform the action.
     *
     * @param  Lease $model The resource entity to perform the authorization check on.
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
        return [LeaseStatus::Request, LeaseStatus::Quotation, LeaseStatus::Option, LeaseStatus::Confirmed];
    }

    /**
     * Method to perform the logic that is coupled to the action class.
     *
     * @param  Lease $model The resource entity to perform the state transition on.
     * @return void
     */
    public static function performActionLogic(Model $model): void
    {
        $model->state()->transitionToCancelled();
    }
}
