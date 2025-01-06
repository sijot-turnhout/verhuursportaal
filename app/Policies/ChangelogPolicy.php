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
 *
 * @package App\Policies
 */
final class ChangelogPolicy
{
    public function create(User $user): bool
    {
        return $user->user_group->notIn(enums: [UserGroup::Leiding]);
    }

    /**
     * Determine whether the user can close a specific changelog.
     * This method checks if the given `User` has permission to close a `Changelog`.
     *
     * @param  User $user             The user attempting to close the changelog.
     * @param  Changelog $changelog   The changelog record that is being closed.
     * @return bool                  Returns true if the user is authorized to close the changelog, false otherwise.
     */
    public function closeChangelog(User $user, Changelog $changelog): bool
    {
        return (UserGroup::Webmaster === $user->user_group || UserGroup::Rvb === $user->user_group || $changelog->user()->is($user))
            && ChangelogStatus::Open === $changelog->status;
    }

    /**
     * Dtermine whether the user can reopen a specific changelog.
     * This method checks if the 'user' has permission to reopen a changelog.
     */
    public function reopenChangelog(User $user, Changelog $changelog): bool
    {
        return $user->user_group->in(enums: [UserGroup::Webmaster, UserGroup::Rvb])
            || $changelog->user()->is($user)
            && $changelog->status->is(ChangelogStatus::Closed);
    }
}
