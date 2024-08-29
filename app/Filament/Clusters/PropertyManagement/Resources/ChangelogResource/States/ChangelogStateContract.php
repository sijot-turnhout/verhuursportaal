<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\States;

/**
 * Contract for managing state transitions of a Changelog.
 *
 * The `ChangelogStateContract` interface defines the methods required for handling state transitions
 * of a `Changelog` within the application. Implementations of this interface are responsible for
 * transitioning a changelog's state to 'open' or 'closed'.
 */
interface ChangelogStateContract
{
    /**
     * Transition the changelog to the 'open' state.
     *
     * This method is responsible for changing the state of the changelog to 'open'.
     * It is typically called when a changelog needs to be reopened for further work or review.
     *
     * @return void
     */
    public function transitionToOpen(): void;

    /**
     * Transition the changelog to the 'closed' state.
     *
     * This method is responsible for changing the state of the changelog to 'closed'.
     * It is typically called when the work associated with the changelog is completed or resolved.
     *
     * @return void
     */
    public function transitionToClosed(): void;
}
