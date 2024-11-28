<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserGroup;
use App\Models\User;

/**
 * Defines authorization logic for the Key model, focusing on community-based access control.
 * This policy requlates which uses within the community can perform actions related to Keys
 *
 * @package App\Policies
 */
final readonly class KeyPolicy
{
    /**
     * Determines if a user within the community can view any keys.
     * Members of the 'Rvb' or 'Webmaster' user groups are granted access.
     * This ensures that key visibility is restricted to authorized community members.
     *
     * @param  User $user  The community member for whom to check authorization.
     * @return bool        True if authorized, false otherwise
     * @throws \Exception  If the user group check encounters an unexpected error.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster]);
    }

    /**
     * Determine if a user within the community can create new keys.
     * Authorization is granted to members of the 'Raad van bestuur' or 'Webmaster' user groups.
     * This control measure ensures responsible key creation within the community.
     *
     * @param  User $user  The community member for whom to check authorization.
     * @return bool        True if the user is authorized to create keys, false otherwise.
     * @throws \Exception  If the use group check encounters an unexpected error.
     */
    public function create(User $user): bool
    {
        return $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster]);
    }
}
