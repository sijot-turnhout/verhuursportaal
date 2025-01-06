<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserGroup;
use App\Models\User;

final readonly class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->user_group->is(UserGroup::Webmaster);
    }
}
