<?php

declare(strict_types=1);

namespace App\Support;

final readonly class Features
{
    /**
     * Determine if the given feature is enabled.
     *
     * @param  string  $feature  The name of the feature in the application configuration.
     */
    public static function enabled(string $feature): bool
    {
        return in_array($feature, config('sijot-verhuur.features', []), true);
    }

    /**
     * Enable the registration feature of the feedback from the tenants.
     */
    public static function feedback(): string
    {
        return 'feedback';
    }

    public static function automaticBillingLinesImport(): string
    {
        return 'automatic-billing-lines-import';
    }
}
