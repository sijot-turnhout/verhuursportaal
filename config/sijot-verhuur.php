<?php

declare(strict_types=1);

/**
 * SIJOT verhuur configuration
 *
 * This file contains all the configuration values that are platform related and the working of the platform.
 * For now the basic configuration is applied. But feel free to mpodify the configuration values to your own needs.
 */


return [
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
];
