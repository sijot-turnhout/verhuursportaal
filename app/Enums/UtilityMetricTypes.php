<?php


declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use UnhandledMatchError;

/**
 * Enum UtilityMetricTypes
 *
 * Represents various types of utility metrics tracked within the application.
 * This enum is used to categorize and manage different types of resource consumption,
 * such as gas, water, and electricity usage. Each metric type is associated with
 * an icon for UI representation and a suffix for unit measurement.
 *
 * @package App\Enums
 */
enum UtilityMetricTypes: string implements HasIcon
{
    /**
     * Gas Consumption
     *
     * Represents the consumption of gas.
     * This metric type tracks the usage of gas within the system, typically measured
     * in cubic meters (m3). It is associated with a fire icon for easy identification in the UI.
     */
    case Gas = 'Gas verbruik';

    /**
     * Water Consumption
     *
     * Represents the consumption of water.
     * This metric type tracks the usage of water, typically measured in liters (L).
     * It is associated with an upward trending arrow icon to represent flow or consumption.
     */
    case Water = 'Water verbruik';

    /**
     * Electricity Consumption
     *
     * Represents the consumption of electricity.
     * This metric type tracks the usage of electricity, typically measured in kilowatt-hours (KWh).
     * It is associated with a bolt icon, symbolizing electrical energy.
     */
    case Electricity = 'Electriciteit verbruik';

    /**
     * Retrieves a descriptive billing line for the utility type.
     *
     * This method uses a `match` expression to return a specific description for each type of utility,
     * tailored to its usage during a rental period. These descriptions are intended to be displayed
     * on invoices or billing documents, providing clear details about the associated charges.
     *
     * @return string The descriptive billing line corresponding to the utility type.
     */
    public function getBillingLine(): string
    {
        return match ($this) {
            self::Gas => 'Verbruik van gas tijdens de verhuring',
            self::Water => 'Verbruik van water tijdens de verhuring',
            self::Electricity => 'Verbruik van elektriciteit tijdens de verhuring',
        };
    }

    /**
     * Get the associated icon for the utility metric type.
     *
     * This method returns the icon name to be used in the UI for representing the specific
     * utility metric. Icons are selected to visually correspond with the type of resource being tracked.
     *
     * @return string The icon name corresponding to the utility metric type.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Gas => 'heroicon-o-fire',
            self::Electricity => 'heroicon-o-bolt',
            self::Water => 'heroicon-o-arrow-trending-up',
        };
    }

    /**
     * Get the unit suffix associated with the utility metric type.
     *
     * This method returns the suffix to be appended to the numerical value of the metric,
     * representing the unit of measurement (e.g., m3 for gas, L for water, KWh for electricity).
     *
     * @return string  The unit suffix corresponding to the utility metric type.
     */
    public function getSuffix(): string
    {
        return match ($this) {
            self::Gas => 'm3',
            self::Water => 'L',
            self::Electricity => 'KWh',
        };
    }
}
