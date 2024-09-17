<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\States;

use App\Models\Issue;
use Exception;

/**
 * Class IssueState
 *
 * Represents the base state for an issue in the property management system.
 * This class provides default behavior for state transitions and prevents invalid state transitions.
 * Each specific state of an issue should extend this class and override the methods as needed.
 */
class IssueState implements IssueStateContract
{
    public function __construct(
        public readonly Issue $issue,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function transitionToClosed(): void
    {
        throw new Exception('Cannot close an issue that is already closed');
    }

    /**
     * {@inheritDoc}
     */
    public function transitionToOpen(): void
    {
        throw new Exception('Cannot reopen an issue that is already open');
    }
}
