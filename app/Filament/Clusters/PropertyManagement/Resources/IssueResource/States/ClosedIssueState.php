<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\States;

use App\Filament\Resources\LocalResource\Enums\Status;

/**
 * Represents the closed state of an issue within the property management cluster.
 *
 * The `ClosedIssueState` class extends the `IssueState` class and is responsible for
 * handling the behavior and logic specific to issues that are in the "closed" state.
 * When an issue is closed, it signifies that the issue has been resolved or is no longer
 * active. This state may trigger specific actions, such as notifying users or archiving
 * the issue.
 *
 * This class should implement any methods from the `IssueStateContract` interface, ensuring
 * that transitions into or out of the closed state are handled appropriately.
 *
 * Key Responsibilities:
 * - Manage the business logic specific to closed issues.
 * - Facilitate state transitions, ensuring consistency and integrity of the issue's lifecycle.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\IssueResource\States
 */
final class ClosedIssueState extends IssueState
{
    /**
     * {@inheritDoc}
     */
    public function transitionToOpen(): void
    {
        $this->issue->update(['status' => Status::Open]);
    }
}
