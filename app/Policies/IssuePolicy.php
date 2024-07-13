<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;

final readonly class IssuePolicy
{
    /**
     * Determine whether the current authenticated user is authorized to delete the issue ticket.
     *
     * @param  User  $user  The database entity from the current authenticated user
     * @param  Issue  $issue  The database entity from the provided issue ticket
     */
    public function delete(User $user, Issue $issue): bool
    {
        return $user->owns($issue, 'creator_id')
            || $user->owns($issue, 'user_id')
            || $user->user_group->isWebmaster();
    }

    /**
     * Determine whether the current authenticated user is authorized to update the issue ticket.
     *
     * @param  User  $user  The database entity from the current authenticated user
     * @param  Issue  $issue  The database entity from the provided issue ticket
     */
    public function update(User $user, Issue $issue): bool
    {
        return $user->owns($issue, 'creator_id')
            || $user->owns($issue, 'user_id')
            || $user->user_group->isWebmaster()
            || $user->user_group->isRvb()
            && null !== $issue->closed_at;
    }

    /**
     * Determine whether the current authenticated user is authorized to close the issue ticket.
     *
     * @param  User  $user  The database entity from the current authenticated user
     * @param  Issue  $issue  The database entity from the provided issue ticket
     */
    public function close(User $user, Issue $issue): bool
    {
        if ($issue->status->isClosedIssueTicket()) {
            return false; // Can't close issue tickets that are already closed.
        }

        // Proceed with the normalized check to confirm that the authenticated user is allowed to perform the handling.
        return $user->owns($issue, 'creator_id')
            || $user->owns($issue, 'user_id')
            || $user->user_group->isWebmaster();
    }

    /**
     * Determine whether the current authenticated user is authorized to reopen the issue ticket.
     *
     * @param  User  $user  The database entity from the current authenticated user
     * @param  Issue  $issue  The database entity from the provided issue ticket
     */
    public function reopen(User $user, Issue $issue): bool
    {
        if ($issue->status->isOpenIssueTicket()) {
            return false; // Can't reopen issue tickets that are already open.
        }

        // Proceed with the the normalized check to confirm that the authenticated user is allowed to perform the handling.
        return $user->owns($issue, 'creator_id')
            || $user->owns($issue, 'user_id')
            || $user->user_group->isWebmaster();
    }
}
