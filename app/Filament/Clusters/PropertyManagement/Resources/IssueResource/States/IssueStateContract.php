<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\States;

/**
 * Interface for managing state transitions of an issue.
 *
 * The `IssueStateContract` interface defines the contract for handling state transitions
 * within the `IssueResource`. Implementing this interface ensures that any class managing
 * the state of an issue provides methods to transition between different states, such as
 * opening or closing an issue.
 *
 * Implementations of this interface should handle the necessary logic for each state
 * transition, ensuring the issue's state is accurately reflected within the application.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\IssueResource\States
 */
interface IssueStateContract
{
    /**
     * Transitions the issue to a closed state.
     *
     * This method should handle all necessary actions to change the state of an issue
     * from its current state to a closed state. This could involve updating the database,
     * logging the transition, and notifying relevant parties.
     *
     * @return void
     */
    public function transitionToClosed(): void;

    /**
     * Transitions the issue to an open state.
     *
     * This method should handle all necessary actions to change the state of an issue
     * from its current state to an open state. This could involve updating the database,
     * logging the transition, and notifying relevant parties.
     *
     * @return void
     */
    public function transitionToOpen(): void;
}
