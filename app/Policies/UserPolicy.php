<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

/**
 * Class UserPolicy
 *
 * This class governs access control for user-related actions within our application.
 * It plays a vital role in safeguarding user data and fostering a secure environment for our application (community).
 * By defining clear permissions for actions like viewing, creating, updating, and deleting user records, we ensure responsible data handling and build trust among our users.
 *
 * These policies are designed to be transparent and understandable.
 * We encourage community feedback and suggestions for improvements to these policies to best serve the needs of our users.
 * Discussion and contributions are welcome through our established communication channels.
 *
 * @package App\Policies
 */
final class UserPolicy
{
    /**
     * Determines if a user can view a list of all users.
     *
     * Providing an overview of all users is a critical administrative function.
     * This permission is typically granted to roles like Webmaster or RVB, who are responsible for managing the user base and ensuring the smooth operation of the platform.
     * This access allows them to monitor user activity, manage permissions, and address any community-related issues effectively.
     *
     * @param  User $user  The user initiating the request.
     * @return bool        True if authorized, false otherwise.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }

    /**
     * Determines if a user can view the details of a specific user.
     *
     * Access to individual user details requires careful control to protect privacy.
     * This method ensures that only authorized personnel, such as Webmasters or RVB members, can access sensitive information.
     * This restriction is in place to respect user privacy and maintain a secure community environment.
     *
     * @param  User $user   The user initiating the request.
     * @param  User $model  The user whose details are being requested.
     * @return bool         True if authorized, false otherwise.
     */
    public function view(User $user, User $model): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }

    /**
     * Determines if user can create new user accounts.
     *
     * The ability to create new users is a sensitive operation and should be restricted to trusted roles.
     * Limiting this action to webmasters and RVB members helps prevent unauthorized account creation and maintains the integrity of our user base.
     * This contributes to a healthier and more secure community.
     *
     * @param  User $user  The user initiating the request
     * @return bool        True is authorized, false otherwise.
     */
    public function create(User $user): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }

    /**
     * Determine if a user can update the details of a specific user.
     *
     * Modifying user information requires appropriate authorization.
     * This method ensures that only designated roles, such as Webmasters or RVB members can update user details.
     * This restriction protects against unauthorized changes and helps maintain data accuracy, contributing to a more reliable community experience.
     *
     * @param  User $user   The user initiating the request.
     * @param  User $model  The user whose details are being updated.
     * @return bool         True if authorized, false otherwise.
     */
    public function update(User $user, User $model): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }

    /**
     * Determine if a user can delete a user account.
     *
     * Deleting user account is a critical action with signification implications in the application.
     * This method ensures that only authorized individuals, specifically Webmasters and RVB members, can perform this action.
     * Strict control over account deletion is crucial for preventing accidental data loss and maintaining the integrity of our community platform.
     *
     * @param  User  $user   The user initiating the request.
     * @param  User  $model  The user account being deleted.
     * @return bool          True if authorized, false otherwise.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->user_group->isWebmaster() || $user->user_group->isRvb();
    }
}
