<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LeaseStatus: string implements HasColor, HasIcon, HasLabel
{
    case Quotation = 'optie (offerte)';
    case Request = 'nieuwe aanvraag';
    case Option = 'optie';
    case Confirmed = 'bevestigd';
    case Finalized = 'afgesloten';
    case Cancelled = 'geannuleerd';



    public function getColor(): string|array|null
    {
        return match($this) {
            self::Request => 'info',
            self::Option, self::Quotation => 'warning',
            self::Confirmed => 'success',
            self::Finalized, self::Cancelled => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match($this) {
            self::Request => 'heroicon-m-plus-circle',
            self::Option, self::Quotation => 'heroicon-m-document-text',
            self::Confirmed => 'heroicon-m-check-badge',
            self::Finalized => 'heroicon-m-document-check',
            self::Cancelled => 'heroicon-m-archive-box-x-mark',
        };
    }

    public function getLabel(): string
    {
        return trans($this->value);
    }
}
