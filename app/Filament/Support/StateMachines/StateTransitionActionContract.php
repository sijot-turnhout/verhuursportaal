<?php

declare(strict_types=1);

namespace App\Filament\Support\StateMachines;

use Illuminate\Database\Eloquent\Model;

/**
 * Contract for defining state transition actions within a state machine.
 *
 * This interface outlines the required methods for handling state transitions
 * in a consistent manner. Implementing this contract ensures that state transitions
 * are well-defined and reusable across different models.
 *
 * @package App\Filament\Support\StateMachines
 */
interface StateTransitionActionContract
{
    /**
     * Checks if the given model is eligible for the state transition.
     *
     * Implementations should define the conditions under which a model
     * can transition to the target state.
     *
     * @param  Model $model  The model to evaluate.
     * @return bool          True if the transition is allowed, false otherwise.
     */
    public static function canTransition(Model $model): bool;

    /**
     * Configures the list of states from which a transition to the target state is allowed.
     *
     * This method defines the starting states that can lead to the target state
     * for the action implementing this interface.
     *
     * @return array The list of allowed states for the transition.
     */
    public static function configureAllowedStates(): array;

    /**
     * Executes the logic for performing the state transition.
     *
     * This method should handle the actual transition logic, such as updating
     * the model's state and any related side effects.
     *
     * @param  Model $model The model undergoing the state transition.
     * @return void
     */
    public static function performActionLogic(Model $model): void;
}
