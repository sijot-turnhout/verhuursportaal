<?php


declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserGroup;
use App\Models\Tenant;
use App\Models\User;

/**
 * Class TenantPolicy
 *
 * This policy class manages authorization rules for tenant-related operations within the rental portal.
 * It controls access to tenant management functions based on user permissions and tenant status.
 * The class is marked as final and readonly to ensure immutability and prevent inheritance.
 *
 * @package App\Policies
 */
final readonly class TenantPolicy
{
    /**
     * Determine whether the given user can deactivate the specified tenant.
     *
     * This authorization check ensures that only active (non-banned) tenants can be
     * deactivated, preventing redundant deactivation operations on already inactive tenants.
     *
     * @param  User   $user    The user attempting the deactivation
     * @param  Tenant $tenant  The tenant to be deactivated
     * @return bool             True if the tenant is currently active and can be deactivated
     */
    public function deactivate(User $user, Tenant $tenant): bool
    {
        return $tenant->isNotBanned();
    }

    /**
     * Determine whether the given user can create new tenants.
     *
     * This method restricts tenant creation based on the user's group membership.
     * Users in the 'Leiding' group are specifically prohibited from creating new tenants, while all other user groups are permitted to do so.
     *
     * @param  User $user  The user attempting to create a tenant
     * @return bool        True if the user is not in the 'Leiding' group
     */
    public function create(User $user): bool
    {
        return $user->user_group->notIn(enums: [UserGroup::Leiding]);
    }

    /**
     * Determine whether the given user can activate the specified tenant.
     *
     * This authorization check ensures that only inactive (banned) tenants can be
     * activated, preventing redundant activation operations on already active tenants.
     *
     * @param  User   $user    The user attempting the activation
     * @param  Tenant $tenant  The tenant to be activated
     * @return bool            True if the tenant is currently banned and can be activated
     */
    public function activate(User $user, Tenant $tenant): bool
    {
        return $tenant->isBanned();
    }
}
