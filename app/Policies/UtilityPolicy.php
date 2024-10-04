<?php

declare(strict_types=1);

namespace App\Policies;

use App\Features\UtilityMetrics;
use App\Models\User;
use App\Models\Utility;
use Laravel\Pennant\Feature;

/**
 * Class UtilityPolicy
 *
 * This policy handles authorization checks for utilities, including whether a user
 * can update utility records based on specific conditions such as feature activation
 * and the finalization status of utility metrics.
 *
 * @package App\Policies
 */
final readonly class UtilityPolicy
{
    /**
     * Checks if the utility metrics feature is enabled in the application.
     *
     * The utility metrics registration is a feature flag, and this method verifies
     * if the feature is currently active using the `Laravel\Pennant\Feature` class.
     *
     * @return bool  Returns true if the utility metrics feature is enabled, false otherwise.
     */
    public function featureEnabled(): bool
    {
        return Feature::active(UtilityMetrics::class);
    }

    /**
     * Determines if the user is authorized to update the utility.
     *
     * The user can only update the utility if the metrics feature is enabled and
     * the associated lease has not yet finalized its utility metrics.
     *
     * @param  User     $user     The user attempting to update the utility.
     * @param  Utility  $utility  The utility model being updated.
     * @return bool               True if the user can update the utility, false otherwise.
     */
    public function update(User $user, Utility $utility): bool
    {
        return $this->featureEnabled() && $utility->lease->hasntFinalizedUtilityMetrics();
    }
}
