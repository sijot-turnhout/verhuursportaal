<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\States;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Enums\ChangelogStatus;

/**
 * Represents the 'open' state of a changelog.
 *
 * The `OpenChangelogState` class extends the `ChangelogStatusState` and provides the
 * implementation for transitioning a changelog from the 'open' state to the 'closed' state.
 * This class is part of the state pattern used to manage the different states a changelog
 * can be in within the application.
 */
final class OpenChangelogState extends ChangelogStatusState
{
    /**
     * {@inheritDoc}
     */
    public function transitionToClosed(): void
    {
        $this->changelog->update(['status' => ChangelogStatus::Closed]);
    }
}
