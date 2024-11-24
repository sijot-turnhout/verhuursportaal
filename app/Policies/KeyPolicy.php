<?php

namespace App\Policies;

use App\Enums\UserGroup;
use App\Models\User;

final readonly class KeyPolicy
{
    public function create(User $user): bool
    {
        return $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Webmaster]);
    }
}
