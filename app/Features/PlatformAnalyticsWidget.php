<?php

declare(strict_types=1);

namespace App\Features;

/**
 * PlatformAnalyticsWidget
 *
 * This class represents a feature that controls the visibility or activation of the platform analytics widget.
 * The `resolve` method returns the initial value for this feature, which is set to `false` by default.
 *
 * @package App\Features
 */
final readonly class PlatformAnalyticsWidget
{
    /**
     * Resolve the feature's initial value.
     *
     * This method is used to determine whether the platform analytics widget should be enabled or disabled.
     * By default, it returns `false`, indicating the feature is not active.
     *
     * @param  mixed $scope The context in which the feature is being resolved. This can be used to dynamically decide the feature's state.
     * @return bool         The initial value of the feature, defaulting to `false`.
     */
    public function resolve(mixed $scope): bool
    {
        return true;
    }
}
