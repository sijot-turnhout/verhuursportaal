<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserGroup;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Enums\ChangelogStatus;
use App\Models\Changelog;
use App\Models\User;

/**
 * Policy class for managing authorization related to Changelog actions.
 *
 * The `ChangelogPolicy` class defines various authorization rules that determine whether
 * a user has the permissions to perform specific actions on the `Changelog` model.
 * This class is used by Laravel's authorization system to enforce these rules.
 */
final class ChangelogPolicy
{
    /**
     * Determine whether the user can close a specific changelog.
     * This method checks if the given `User` has permission to close a `Changelog`.
     *
     * A changelog can be closed by:
     * - Users in the `Webmaster` or `Rvb` user group.
     * - The user who is responsible for the follow-up of the changelog.
     * - Changelogs that has the open status
     *
     * @param User $user             The user attempting to close the changelog.
     * @param Changelog $changelog   The changelog record that is being closed.
     *
     * @return bool                  Returns true if the user is authorized to close the changelog, false otherwise.
     */
    public function closeChangelog(User $user, Changelog $changelog): bool
    {
        return ($user->user_group === UserGroup::Webmaster || $user->user_group === UserGroup::Rvb || $changelog->user()->is($user))
            && $changelog->status === ChangelogStatus::Open;
    }

    /**
     * Dtermine whether the user can reopen a specific changelog.
     * This method checks if the 'user' has permission to reopen a changelog.
     *
     * A changelog can be reopened by:
     * - Users wint the 'webmaster' or 'rvb' user group.
     * - The user who is responsible for the follow-up of the changelog.
     * - Changelogs that has the closed status
     */
    public function reopenChangelog(User $user, Changelog $changelog): bool
    {
        return ($user->user_group === UserGroup::Webmaster || $user->user_group === UserGroup::Rvb || $changelog->user()->is($user))
            && $changelog->status === ChangelogStatus::Closed;
    }
}
