<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }

    public function view(User $user, User $model): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }

    public function update(User $user, User $model): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }
}
