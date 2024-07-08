<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Utility;
use App\Support\Features;

final readonly class UtilityPolicy
{
    /**
     * Authorization check interception.
     *
     * This check will be executed before the intended policy is actually called.
     * We've implemented this check because the registration of utility usage metrics is an optional feature in the application.
     */
    public function featureEnabled(): bool
    {
        return Features::enabled(Features::utilityMetrics());
    }

    public function update(User $user, Utility $utility): bool
    {
        return $this->featureEnabled() && $utility->lease->hasntFinalizedUtilityMetrics();
    }
}
