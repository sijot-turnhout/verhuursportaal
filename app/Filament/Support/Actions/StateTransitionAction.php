<?php

declare(strict_types=1);

namespace App\Filament\Support\Actions;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * Abstract class representing a state transition action in Filament.
 *
 * This class provides a base implementation for actions that transition a model to a new state.
 * It defines abstract methods for checking transition eligibility, configuring allowed states,
 * and performing the actual state transition logic.
 *
 * @package App\Filament\Support\Actions
 */
abstract class StateTransitionAction extends Action
{
    /**
     * Determines if the given model can be transitioned to a new state.
     *
     * @param  Model $model The given model to check.
     * @return bool         'true' if the model can ben transitioned. 'false' otherwise.
     */
    abstract public static function canTransition(Model $model): bool;

    /**
     * Configures the allowed states for the transition.
     *
     * @return array An array of the allowed states.
     */
    abstract public static function configureAllowedStates(): array;

    /**
     * Performs the actual state transition logic on the given model.
     *
     * @param  Model $model The model entity to transition
     * @return void
     */
    abstract public static function performActionLogic(Model $model): void;

    /**
     * Creates a new instance of the action with the specified configuration.
     *
     * @param  string|null $name        The name of the action.
     * @param  string      $label       The label of the action
     * @param  mixed       $finalState  The final state of the transition.
     * @return static
     */
    public static function buildStateTransitionAction(string $label, mixed $finalState, ?string $name = null): static
    {
        return parent::make($name)
            ->label($label)
            ->translateLabel()
            ->color($finalState->getColor())
            ->icon($finalState->getIcon());
    }
}
