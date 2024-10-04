<?php

namespace App\Features;

use Illuminate\Support\Lottery;

/**
 * The Feedback feature class is responsible for managing whether the feedback functionality is enabled.
 *
 * This feature can be resolved based on different scopes, such as user, tenant, or any other context,
 * allowing dynamic control over the availability of feedback-related actions in the application.
 *
 * @package App\Features
 */
final class Feedback
{
    /**
     * Resolve whether the feedback feature should be enabled for a given scope.
     *
     * This method determines if the feedback feature should be active based on the provided scope.
     * By default, this method returns `true`, indicating that the feature is enabled.
     *
     * @param  mixed  $scope  The scope for which the feedback feature is being resolved. The scope could be user-related, tenant-related, or any other context.
     * @return bool           Returns `true` to enable the feedback feature for the given scope.
     */
    public function resolve(mixed $scope): bool
    {
        return true;
    }
}
