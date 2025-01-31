<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * IncidentCodes Enum
 *
 * This enumeration represents different types of incidents that can occur during a rental period.
 * It is used to categorize and manage violations, enabling structured handling of issues.
 *
 * Each incident type has:
 * - A label (user-friendly name).
 * - A Description (detailed explanation of the issue).
 * - An Icon (for visual representation in the UI).
 * - A color (for status indication).
 *
 * This enum is primarily used in Filament-based admin panels to help rental managers
 * quickly identify and address common problems and or incidents.
 *
 * Implements:
 * - HasDescription: Provides a detailed explanation of the incident.
 * - HasLabel: Returns the display name for UI representation.
 * - HasIcon: Defines an icon for quick identification.
 * - HasColor: Assigns a visual status indicator (e.g., warning, danger, ...)
 *
 * @package App\Enums
 */
enum IncidentCodes: string implements HasDescription, HasLabel, HasIcon, HasColor
{
    /** The tenant has made a late paym-ent, protentially effecting financial stability. */
    case LatePayment = 'Achterstallige betaling';

    /** The tenant has casued damage to the rented property, requiring reparis. */
    case PropertyDamage = 'Schade aan eigendommen';

    /** Unauthorized fire use, such as campfires in restricted areas of the domain. */
    case UnauthorizedFire = 'Ongeoorloofd vuurgebruik';

    /** Safety rules have been violated, posing risks to tenants or future leases. */
    case SafetyViolation = 'Veiligheidovertreding';

    /** Actions harmful to the environment, such as illegal waste displosal. */
    case EnvironmentalImpact = 'Ecologische inbreuk';

    /** Violation of house regulations agreed upon the start of the rental. */
    case RuleViolation = 'Inbreuk huisregelement';

    /** The number of guests exceeds the agreed-upon capacity. */
    case ExtraGuest = 'Overcapaciteit';

    /** Other violations that do not fit into the predefined categories. */
    case Other = 'Andere inbreuken';

    /**
     * Retrieves the label for the incident.
     *
     * @return string  A short, user-friendly label for the incident code.
     */
    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * Provides a detailed description of the incident.
     *
     * @todo Investigate whether PHP attributes can be used for storing descriptions.
     *
     * @return string A translated description explaining the nature of the incident.
     */
    public function getDescription(): string
    {
        $description = match ($this) {
            self::LatePayment => 'Een te late betaling, wat een risico vormt voor de financiële stabiliteit.',
            self::PropertyDamage => 'Schade aan gehuurd eigendom, wat voor extra herstelkosten kan zorgen.',
            self::UnauthorizedFire => 'Ongeoorloofd gebruik van vuur, zoals kampvuren op de niet aangegeven locaties',
            self::SafetyViolation => "Niet naleven van veiligheidsregels, wat risico's voor de huurder of gasten met zich meebrengt",
            self::EnvironmentalImpact => 'Acties die schadelijk zijn voor het milieu, zoals het sluikstoren van afval na de verhuring',
            self::RuleViolation => 'Inbreuk op het huisregelement dat is ondertekend bij aanvang van de verhuring',
            self::ExtraGuest => 'Er zijn meer gasten op het domein dan aangegeven bij aanvang van de verhuring',
            self::Other => 'Andere inbreuken die niet geplaatst kunnen worden in de bovenstaande categorieen',
        };

        return trans($description);
    }

    /**
     * Retrieves the color associated with the incident.
     * Colors are used for UI elements such as badges or status indicators.
     *
     * @return string A color name (e.g., 'warning', 'danger', 'info') corresponding to the incident type.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::LatePayment, self::RuleViolation, self::ExtraGuest => 'warning',
            self::PropertyDamage, self::UnauthorizedFire, self::SafetyViolation => 'danger',
            self::EnvironmentalImpact => 'gray',
            self::Other => 'info',
        };
    }

    /**
     * Retrieves the associated icon for the incident.
     *
     * Icons are used in the UI to quickly convey the type of incident.
     * This mapping uses **Heroicons** (https://heroicons.com).
     *
     * @return string A Heroicon class name for the icon representation.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::LatePayment => 'heroicon-o-document-currency-dollar',
            self::PropertyDamage => 'heroicon-o-wrench',
            self::UnauthorizedFire => 'heroicon-o-fire',
            self::SafetyViolation => 'heroicon-o-shield-exclamation',
            self::EnvironmentalImpact => 'heroicon-o-globe-europe-africa',
            self::RuleViolation => 'heroicon-o-document-text',
            self::ExtraGuest => 'heroicon-o-users',
            self::Other => 'heroicon-o-exclamation-circle',
        };
    }
}
