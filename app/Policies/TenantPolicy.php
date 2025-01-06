<?php


declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserGroup;
use App\Models\Tenant;
use App\Models\User;

final readonly class TenantPolicy
{
    public function deactivate(User $user, Tenant $tenant): bool
    {
        return $tenant->isNotBanned();
    }

    public function create(User $user): bool
    {
        return $user->user_group->notIn(enums: [UserGroup::Leiding]);
    }

    public function activate(User $user, Tenant $tenant): bool
    {
        return $tenant->isBanned();
    }
}
