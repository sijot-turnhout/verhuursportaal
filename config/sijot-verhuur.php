<?php

declare(strict_types=1);

/**
 * SIJOT verhuur configuration
 *
 * This file contains all the configuration values that are platform related and the working of the platform.
 * For now the basic configuration is applied. But feel free to mpodify the configuration values to your own needs.
 */

use App\Support\Features;

return [
    /**
     * @todo document this configuration in a later phase of the project.
     */
    'billing' => [
        'price_per_night' => '5.5',
        'guarantee_payment_amount' => 250,

        'utilities' => [
            'gas' => '1.50',
            'water' => '1.50',
            'electricity' => '1.50',
        ],
    ],

    'server' => [
        'shared' => true,
    ],

    /**
     * -------------------------------------------------------------------------------------------------
     * Feature configuration
     * -------------------------------------------------------------------------------------------------
     *
     * In the array below u can enable of disable built-in features of the application.
     * So you can easily customize it to your own needs as organization.
     * For Simplicity, we've registered all the features. If you decide to not use some specific feature
     * you can delete or comment the feature declaration.
     */
    'features' => [
        Features::utilityMetrics(),
        Features::automaticBillingLinesImport(),
        Features::feedback(),
    ],
];
