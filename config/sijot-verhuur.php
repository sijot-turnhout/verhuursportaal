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
        'automatic_invoicing' => true, // TODO: Document config flag
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
     * Configuration for risk assessment thresholds, mapping risk levels to numeric values.
     *
     * Each risk level represents a threshold score indicating the severity or likelihood of risk,
     * useful for assessing and categorizing risks in the application.
     *
     * @see https://sijot-turnhout.github.io/verhuur-portaal-documentatie/leases/incidents.html#configuratie-van-de-tresholds-voor-de-risico-profielen
     */
    'risk_accessment' => [
        'very_low' => 10,   // Threshold for a 'Very low' risk level, representing minimal concern.
        'low' => 20,        // Threshold for a "Low" risk level, indicating below-averages risk.
        'medium' => 35,     // Threshold for a 'medium' risk level, signifying moderate risk.
        'high' => 50,       // Threshold for a "high risk level, associated with above average risk."
    ],

    'deposit' => [
        'default_amount' => 350, // Default price for the security deposit in a lease.
    ],
];
