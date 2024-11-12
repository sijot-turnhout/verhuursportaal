<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\States;

use App\Models\Changelog;
use Exception;

/**
 * Base class for handling changelog status state transitions.
 *
 * The `ChangelogStatusState` class provides a foundational implementation of the
 * `ChangelogStateContract` interface. It represents a state in which a changelog can
 * either be transitioned to 'open' or 'closed'. This base class, however, throws an
 * exception by default, indicating that state-specific handling must be implemented
 * in subclasses.
 */
class ChangelogStatusState implements ChangelogStateContract
{
    /**
     * The changelog instance being managed.
     *
     * @param  Changelog  $changelog  The changelog model instance that this state class is handling.
     * @return void
     */
    public function __construct(
        public readonly Changelog $changelog,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function transitionToOpen(): void
    {
        throw new Exception('Cannot perform the handling on the current state');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToClosed(): void
    {
        throw new Exception('Cannot perform the handling on the current state');
    }
}
