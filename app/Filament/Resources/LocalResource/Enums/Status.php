<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasColor, HasIcon, HasLabel
{
    case Open = 'open';
    case Closed = 'gesloten';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'danger',
            self::Closed => 'success',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Closed => 'Gesloten',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Open => 'heroicon-o-exclamation-circle',
            self::Closed => 'heroicon-o-check-circle',
        };
    }

    public function isOpenIssueTicket(): bool
    {
        return self::Open === $this;
    }

    public function isClosedIssueTicket(): bool
    {
        return self::Closed === $this;
    }
}
