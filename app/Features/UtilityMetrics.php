<?php

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;

/**
 * Class UtilityMetrics
 *
 * This class defines the utility metrics feature, including how its initial value is resolved for a user.
 * It is part of the feature flag system, controlling whether utility metrics are enabled for specific users or contexts.
 *
 * @package App\Features
 */
final class UtilityMetrics
{
    /**
     * Resolve the initial value of the utility metrics feature for the given user.
     *
     * This method determines whether the utility metrics feature should be enabled for a specific user.
     * The default behavior is to disable the feature by returning `false`.
     *
     * @param  User  $user  The user for whom the feature's value is being resolved.
     * @return bool         Returns `false` to indicate that the feature is disabled by default.
     */
    public function resolve(User $user): bool
    {
        return false;
    }
}
