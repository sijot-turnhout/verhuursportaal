<?php

declare(strict_types=1);

namespace App\Features;

/**
 * The AutomaticBillingLinesImport feature manages the automation of billing lines import.
 *
 * This class determines whether the automatic import of billing lines should be enabled or disabled,
 * based on the provided scope, such as user, tenant, or system settings. It allows for dynamic
 * configuration of billing line imports in the application.
 *
 * @package App\Features
 */
final class AutomaticBillingLinesImport
{
    /**
     * Resolve whether the automatic billing lines import feature should be enabled for a given scope.
     *
     * This method returns `true` by default, indicating that the automatic import feature is enabled
     * for the specified scope.
     *
     * @param  mixed  $scope  The context or scope (e.g., user, tenant) in which the feature is being resolved.
     * @return bool           Returns `true` if automatic billing lines import is enabled for the given scope.
     */
    public function resolve(mixed $scope): bool
    {
        return true;
    }
}
