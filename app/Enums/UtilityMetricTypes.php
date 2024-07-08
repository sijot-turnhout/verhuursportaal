<?php


declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;

enum UtilityMetricTypes: string implements HasIcon
{
    case Gas = 'Gas verbruik';
    case Water = 'Water verbruik';
    case Electricity = 'Electriciteit verbruik';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Gas => 'heroicon-o-fire',
            self::Electricity => 'heroicon-o-bolt',
            self::Water => 'heroicon-o-arrow-trending-up',
        };
    }

    public function getSuffix(): ?string
    {
        return match ($this) {
            self::Gas => 'm3',
            self::Water => 'L',
            self::Electricity => 'KWh',
        };
    }
}
