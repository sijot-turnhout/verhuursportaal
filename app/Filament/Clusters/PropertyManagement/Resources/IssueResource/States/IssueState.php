<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\States;

use App\Models\Issue;
use Exception;

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
