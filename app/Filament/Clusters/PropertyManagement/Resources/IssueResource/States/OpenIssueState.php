<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\States;

use App\Filament\Resources\LocalResource\Enums\Status;

/**
 * Represents the open state of an issue within the property management cluster.
 *
 * The `OpenIssueState` class extends the `IssueState` class and is responsible for
 * managing the behavior and logic specific to issues that are in the "open" state.
 * When an issue is open, it signifies that the issue is active, unresolved, and likely
 * requires attention or action. This state may trigger actions such as notifications,
 * assigning tasks, or setting deadlines.
 *
 * This class should implement any methods from the `IssueStateContract` interface, ensuring
 * that transitions into or out of the open state are handled appropriately.
 *
 * Key Responsibilities:
 * - Manage the business logic specific to open issues.
 * - Facilitate state transitions, ensuring the issue lifecycle is maintained correctly.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\IssueResource\States
 */
final class OpenIssueState extends IssueState
{
    /**
     * {@inheritDoc}
     */
    public function transitionToClosed(): void
    {
        $this->issue->update(['status' => Status::Closed, 'closed_at' => now()]);
    }
}
