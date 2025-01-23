<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum IncidentImpact: int implements HasColor, HasLabel, HasIcon
{
    case Unknown = 0;
    case VeryLow = 1;
    case Low = 2;
    case Normal = 3;
    case High = 4;
    case VeryHigh = 5;

    /**
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
