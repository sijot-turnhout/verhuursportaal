<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\States;

/**
 * Represents the 'closed' state of a changelog.
 *
 * The `ClosedChangelogState` class extends the `ChangelogStatusState` and provides the
 * implementation for transitioning a changelog from the 'closed' state back to the 'open' state.
 * This class is part of the state pattern used to manage the different states a changelog
 * can be in within the application.
 */
final class ClosedChangelogState extends ChangelogStatusState
{
    /**
     * {@inheritDoc}
     */
    public function transitionToOpen(): void
    {

    }
}
