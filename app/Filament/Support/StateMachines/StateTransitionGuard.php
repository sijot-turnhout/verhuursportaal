<?php

declare(strict_types=1);

namespace App\Filament\Support\StateMachines;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Trait StateTransitionGuard
 *
 * This trait provides a reusable implementation of the logic for checking whether a state
 * transition is allowed for a given model. It checks if the current state of the model permits
 * a transition to one or more allowed states, which can be passed as a string or an array of states.
 *
 * This trait can be used within classes that need to enforce state transition rules across different models.
 *
 * @todo Replace this to the filament support directory because we can use this on multiple state machines
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
trait StateTransitionGuard
{
    /**
     * Determines if the transition to one or more allowed states is permitted for the given model.
     *
     * This method checks if the current state of the provided model allows a transition
     * to the specified state(s). The allowed states can be provided as either a single state
     * or an array of states.
     *
     * @param  Model $model          The model instance on which the state transition is being attempted.
     * @param  array $allowedStates  An array of states that the model should not be in for the transition to be allowed.
     * @return bool                  True if the transition to the specified state(s) is allowed, false otherwise.
     */
    public function allowTransitionTo(Model $model, array $allowedStates): bool
    {
        return Gate::allows('update', $model) && $model->status->in($allowedStates);
    }
}
