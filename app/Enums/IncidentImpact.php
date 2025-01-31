<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum representing the impact level of an incident.
 *
 * This enumeration categorizes incidents based on their severity level,
 * allowing tthe system to prioritize response and mitigation efforts.
 * It is commonly used in incident magament systems, logging mechanisms,
 * and risk assesment tools to provide structured classification.
 *
 * Impact levels range from "Unknown" to "Very high", ensuring that
 * incidents can be handled according ytto their urgency and severity.
 *
 * @package App\Enums
 */
enum IncidentImpact: int implements HasColor, HasLabel, HasIcon
{
    /**
     * The impact score is unknown or has not been determined yet.
     *
     * This is typically used as a default value when an incident
     * is first reported and requires further assessment before classification.
     *
     * Examples:
     * - A vague complaint form a tenant without specific details.
     * - An alert from a monitoring systelm that requires verification.
     *
     * Implications:
     * - Requires manual of automated analysis to update.
     * - Should not persist in this state for long.
     * - Can include incomplete or missing data.
     */
    case Unknown = 0;

    /**
     * The incident has a very low impact;
     *
     * Typically represents minor issues that do not significantly affect operations, or users.
     * There incidents may be logged for monitoring but generally do not require immediate action.
     *
     * Examples:
     * - A tenant reports a small scuff on a wall.
     * - A flickering lightbulb in a common area.
     * - A door hinge squeaks but still function properly.
     *
     * Implications:
     * - Mat be automatically resolved without intervention.
     * - Can be deprioritized in favor of higher-severity incidents.
     */
    case VeryLow = 1;

    /**
     * The incident has low impact on the domain and their leases.
     *
     * While slightly more significant then a very low impact, these incidents still do not cause major disruption.
     * They may be scheduled for resolution at a later time.
     *
     * Examples:
     * - A leaking faucet that wastes water but does not cause damage.
     * - A heating or air conditioning unit working less efficiently.
     * - A tenant reports minor pest sightings (e.g., occasional ants).
     *
     * Implications:
     * - Requires monitoring but does not need immediate resolution.
     * - May lead to higher impact if left unattended.
     */
    case Low = 2;

    /**
     * The incident has a normal impact.
     *
     * These incidents represent issues that require attention but are not severe enough to cause major disruptions.
     * They may impact a moderate number of tenants or domain infrastructure.
     *
     * Examples:
     * - A partially clogged drain slowing water flow.
     * - A heating system failure during mild weather.
     * - A minor electrical issue affecting a single room.
     *
     * Implications:
     * - Should be addressed within a reasonable timeframe.
     * - May be escalated if additional factors increase its severity.
     */
    case Normal = 3;

    /**
     * The incident has a high impact on the property lease(s).
     *
     * These incidents pose a serious problem that significantly
     * affects tenants or the property's usability.
     *
     * Examples:
     * - A major plumbing issue causing flooding in one unit.
     * - A heating failure during extreme winter temperatures.
     * - A structural issue (e.g., cracks in walls affecting stability).
     * - A tenant is locked out due to a broken lock or security system.
     *
     * Implications:
     * - Requires urgent intervention.
     * - Could lead to property damage or tenant dissatisfaction.
     * - Potential legal implications if not addressed swiftly.
     */
    case High = 4;

    /**
     * The incident has a very high impact on the property lease.
     *
     * These are critical incidents that cause extreme disruptions,
     * significant financial impact, or pose serious safety risks.
     * Immediate emergency response is required.
     *
     * Examples:
     * - A fire or gas leak in the property.
     * - Structural collapse or severe damage making the unit uninhabitable.
     * - A tenant injury due to unsafe conditions (e.g., a balcony collapse).
     * - A break-in or violent incident requiring police involvement.
     *
     * Implications:
     * - Immediate emergency response required (fire department, police, etc.).
     * - Legal consequences and potential liability.
     * - Property may become temporarily or permanently uninhabitable.
     */
    case VeryHigh = 5;

    /**
     * Get the associated color for the current incident impact.
     *
     * The returned color is used to visually represent the status in the application's UI,
     * allowing users to quickly identify the status through consistent color coding.
     *
     * Returns the color corresponding to the incident impact. Possible values: 'info', 'warning', 'succes', 'danger', 'primary'
     *
     * @return string|array<int, string>
     */
    public function getColor(): string|array
    {
        return match ($this) {
            self::Unknown, self::VeryLow => 'gray',
            self::Low => 'success',
            self::Normal => Color::Yellow,
            self::High => 'warning',
            self::VeryHigh => 'danger',
        };
    }

    /**
     * Get the translated label for the current incident impact.
     *
     * Retrieves a human-readable and localized label for the status,
     * suitable for display in various parts of the application.
     *
     * @return string  The localized label corresponding to the lease status.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Unknown => trans('Onbekend'),
            self::VeryLow => trans('Zeer laag'),
            self::Low => trans('Laag'),
            self::Normal => trans('Gemiddeld'),
            self::High => trans('Hoog'),
            self::VeryHigh => trans('Zeer hoog'),
        };
    }

    /**
     * Get the associated icon for the currunt incident impact score.
     *
     * The returned icon represents the status visually in the application's UI,
     * providing an intuitive and immediate understanding of the incident's impact.
     *
     * @see https://heroicons.com/ for reference on available icons.
     *
     * @return string The icon name corresponding to the lease status. Uses Heroicons naming convention.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Unknown => 'heroicon-o-question-mark-circle',
            self::VeryLow, self::Low => 'heroicon-o-exclamation-circle',
            self::Normal => 'heroicon-o-ellipsis-horizontal-circle',
            self::High, self::VeryHigh => 'heroicon-o-x-circle',
        };
    }
}
