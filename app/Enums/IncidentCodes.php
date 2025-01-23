<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum IncidentCodes: string implements HasDescription, HasLabel, HasIcon, HasColor
{
    case LatePayment = 'Achterstallige betaling';
    case PropertyDamage = 'Schade aan eigendommen';
    case UnauthorizedFire = 'Ongeoorloofd vuurgebruik';
    case SafetyViolation = 'Veiligheidovertreding';
    case EnvironmentalImpact = 'Ecologische inbreuk';
    case RuleViolation = 'Inbreuk huisregelement';
    case ExtraGuest = 'Overcapaciteit';
    case Other = 'Andere inbreuken';

    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * @todo Check if we can register the descriptions to php attributes.
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

    public function getColor(): string
    {
        return match ($this) {
            self::LatePayment, self::RuleViolation, self::ExtraGuest => 'warning',
            self::PropertyDamage, self::UnauthorizedFire, self::SafetyViolation => 'danger',
            self::EnvironmentalImpact => 'gray',
            self::Other => 'info',
        };
    }

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
