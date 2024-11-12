<?php

declare(strict_types=1);

namespace App\Filament\Support\StateMachines;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface StateTransitionGuardContract
 *
 * This interface defines a contract for guarding state transitions on a given model.
 * Implementations of this interface should provide logic to determine whether a transition
 * to one or more specific states is allowed for a given model instance. This can be used to
 * enforce business rules or permissions around state changes in a lease or other domain.
 *
 * @todo Replace this to the filament support directory because we can use this on multiple state machines
 *
 * @package App\Filament\Resources\LeaseResource\States
 */
interface StateTransitionGuardContract
{
    /**
     * Determines if the transition to one or more allowed states is permitted for the given model.
     *
     * This method checks if the current state of the provided model allows a transition
     * to the specified state(s). The allowed states can be provided as either a single state
     * or an array of states.
     *
     * @param  Model              $model          The model instance on which the state transition is being attempted.
     * @param  array<int, object> $allowedStates  An array of states that the model should not be in for the transition to be allowed.
     * @return bool                               True if the transition to the specified state(s) is allowed, false otherwise.
     */
    public function allowTransitionTo(Model $model, array $allowedStates): bool;
}
